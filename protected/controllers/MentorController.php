<?php

class MentorController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * {@inheritDoc}
	 * @see CController::accessRules()
	 */
	public function accessRules() 
	{
	    return array(
	        array('allow',
	            'users' => array('@'),
	        ),	  
	        array('deny', // deny all users
	            'users' => array('*'),
	        ),
	    );
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{	   
	    $model = $this->loadModel($id);
   	   
	    // каталог для файлов
	    $dirFile = str_replace('{code_no}', $model['id_organization'], Yii::app()->params['pathDocumets']);
	    $dirFile = str_replace('{module}', 'mentor', $dirFile);
	    $dirFile = str_replace('{id}', $id, $dirFile);
	    
	    // заголовок страницы
	    $this->pageTitle = $model->title;
	    
	    // сохранение информации о визите пользователя
	    VisitNews::saveVisitMentor($id);
	    
	    // если ajax-запрос, то возвращаем в виде json-формата
	    if (Yii::app()->request->isAjaxRequest) {
	        echo CJSON::encode([
	            'title' => $model->title,
	            'content' => $this->renderPartial('_viewAjax', array(
	                'model' => $model,	               
	                'dirFile' => $dirFile,
	                'dirImage' => '',
	                'files' => $model->getListFiles(),
	                'images' => [],
	            ), true, true),
	        ]);
	        Yii::app()->end();
	    }
	    
	    // результат в обычном html-формате
	    return $this->render('view', array(
	        'model' => $model,
	        'dirImage' => '',
	        'dirFile' => $dirFile,
	        'files' => $model->getListFiles(),
	        'images' => [],
	    ));
	    
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($idWay=0)
	{
		$model=new MentorPost;
		if ($idWay > 0)
		{
		    $model->id_mentor_ways = $idWay;
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['MentorPost']))
		{
			$model->attributes=$_POST['MentorPost'];
			if($model->save())
				$this->redirect(array('way','id'=>$model->id_mentor_ways));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
	    if (!$this->checkRight($id))
	    {
	        throw new CHttpException(403);
	    }
	    
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['MentorPost']))
		{
			$model->attributes=$_POST['MentorPost'];
			if($model->save())
			{
			    if (isset($_POST['MentorPost']['deleteFile']))
			    {
			        $model->deleteFiles($_POST['MentorPost']['deleteFile']);
			    }
			    
			    if (Yii::app()->request->isAjaxRequest)
			    {
			        return "OK";
			    }
			    
				$this->redirect(array('way','id'=>$model->id_mentor_ways));
			}
		}
		
		$this->render('update',array(
			'model'=>$model,		    
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
	    if (!$this->checkRight($id))
	    {
	        throw new CHttpException(403);
	    }
		$model = $this->loadModel($id);
		$model->deleteFiles();
		$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('way', 'id'=>$model->id_mentor_ways));
	}

	
	/**
	 * Список направлений по наставничеству
	 */
	public function actionIndex()
	{
	   $model = MentorWays::model()->findAll(['order' => 'name asc']);
	   $this->render('index', [
	       'model' => $model,
	   ]);
	}
	
	/**
	 * Показать посты
	 */
	public function actionWay($id)
	{
	    $modelWay = $this->loadModelWay($id);	   
	    $model = new MentorPostSearch('search');
	    $model->id_mentor_ways = $id;
	    
	    $this->render('way', [
	        'model' => $model,
	        'modelWay' => $modelWay,
	    ]);	
	}

	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return MentorPost the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=MentorPost::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * @param int $id
	 * @throws CHttpException
	 * @return MentorWays
	 */
	public function loadModelWay($id)
	{
	    $model=MentorWays::model()->findByPk($id);
	    if($model===null)
	        throw new CHttpException(404,'The requested page does not exist.');
        return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param MentorPost $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='mentor-post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Проверка прав
	 * @param int $id
	 * @return boolean
	 */
	private function checkRight($id)
	{
	    return Access::checkAccessMentorPost($id);
	}
}
