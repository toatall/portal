<?php
/**
 * @deprecated
 */
class UpdateEodController extends AdminController
{
    
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
                'users'=>User::model()->getAdminsName(),
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
	public function actionView($id,$idTree)
	{
	    throw new CHttpException(410);
		$this->render('view',array(
			'model'=>$this->loadModel($id,$idTree),
            'idTree'=>$idTree,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($idTree)
	{
	    throw new CHttpException(410);
		$model=new UpdateEod;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UpdateEod']))
		{
			$model->attributes=$_POST['UpdateEod'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id,'idTree'=>$idTree));
		}

		$this->render('create',array(
			'model'=>$model,
            'idTree'=>$idTree,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id,$idTree)
	{
	    throw new CHttpException(410);
		$model=$this->loadModel($id,$idTree);                
                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UpdateEod']))
		{
			$model->attributes=$_POST['UpdateEod'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id,'idTree'=>$idTree));
		}

		
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
	    throw new CHttpException(410);
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
	public function actionAdmin($idTree)
	{
	    throw new CHttpException(410);
		if (!Tree::model()->exists('id=:id AND module=:module AND organization=:organization', 
            array(':id'=>$idTree,'module'=>'updateEod',':organization'=>Yii::app()->session['code_no'])))
            throw new CHttpException(404,'Страница не найдена.');
        
        if (!(Yii::app()->user->admin || Access::model()->checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
        
        $model=new UpdateEod('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UpdateEod']))
			$model->attributes=$_GET['UpdateEod'];

		$this->render('admin',array(
			'model'=>$model,
            'idTree'=>$idTree,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id,$idTree)
	{
	    throw new CHttpException(410);
	   	if (!(Yii::app()->user->admin || Access::model()->checkAccessUserForTree($idTree))
            || !Tree::model()->checkParentRight($idTree))
            throw new CHttpException(403,'Доступ запрещен.'); 
            
        $model=UpdateEod::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='update-eod-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
