<?php

/**
 * Manage votes
 * @author alexeevich
 * @see VoteMain
 *
 */
class VoteController extends AdminController
{
	/**
     * Default action
     * @var string
     */
    public $defaultAction = 'admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
	    return array(
	        array('allow',
	            'expression'=>function() { return Yii::app()->user->inRole(['admin']); },
	        ),
	        array('deny',  // deny all users
	            'users'=>array('*'),
	        ),
        );
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new VoteMain;
		
		$listAllOrg = CHtml::listData(Organization::model()->findAll(), 'code', 'fullName');
		
        $model->on_general_page = true;
        $model->orgList = implode('/', $listAllOrg);
        $model->date_start = date('d.m.Y');
        $model->date_end = date('d.m.Y', strtotime('+1 week'));
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['VoteMain']))
		{
			$model->attributes=$_POST['VoteMain'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		    'listAllOrg'=>$listAllOrg,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['VoteMain']))
		{
			$model->attributes=$_POST['VoteMain'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new VoteMain('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['VoteMain']))
			$model->attributes=$_GET['VoteMain'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Управление вопросами
	 * @param integer $idMain идентификатор голосования
	 */
	public function actionAdminQuestion($idMain)
	{
	   $modelMain = $this->loadModel($idMain);
	   $model = new VoteQuestion('search');
	   $model->unsetAttributes();
	   $model->id_main = $idMain;
	   if(isset($_GET['VoteQuestion']))
	       $model->attributes=$_GET['VoteQuestion'];
	   
	   $this->render('adminQuestion',array(
	       'model'=>$model,
	       'modelMain'=>$modelMain,
	   ));
	}
	
	/**
	 * Создание вопроса
	 * @param integer $idMain идентификатор голосования
	 */
	public function actionCreateQuestion($idMain)
	{
	    $modelMain = $this->loadModel($idMain);
	    
	    $model=new VoteQuestion;
	    $model->id_main = $idMain;
	    if(isset($_POST['VoteQuestion']))
	    {
	        $model->attributes=$_POST['VoteQuestion'];
	        if($model->save())
	            $this->redirect(array('viewQuestion','id'=>$model->id));
	    }
	    
	    $this->render('createQuestion',array(
	        'model'=>$model,
	        'modelMain'=>$modelMain,
	    ));	    
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdateQuestion($id)
	{
	    $model=$this->loadModelQuestion($id);
	    
	    // Uncomment the following line if AJAX validation is needed
	    // $this->performAjaxValidation($model);
	    
	    if(isset($_POST['VoteQuestion']))
	    {
	        $model->attributes=$_POST['VoteQuestion'];
	        if($model->save())
	            $this->redirect(array('viewQuestion','id'=>$model->id));
	    }
	    
	    $this->render('updateQuestion',array(
	        'model'=>$model,
	        'modelMain'=>$this->loadModel($model->id_main),
	    ));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewQuestion($id)
	{
	    $model = $this->loadModelQuestion($id);
	    $this->render('viewQuestion',array(
	        'model'=>$model,
	        'modelMain'=>$this->loadModel($model->id_main),
	    ));
	}
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=VoteMain::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
     * Возвращает данные модели VoteQuestion
	 * @param integer $id
	 * @throws CHttpException
	 * @return VoteQuestion
	 */
	public function loadModelQuestion($id)
	{
	    $model=VoteQuestion::model()->findByPk($id);
	    if($model===null)
	       throw new CHttpException(404);
	    return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vote-main-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
