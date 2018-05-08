<?php

/**
 * Manage users
 * @author alexeevich
 * @see AdminController
 */
class UserController extends AdminController
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
	 * @see User
	 */
	public function actionCreate()
	{
		$model=new User;

		if(isset($_POST['User']))
		{
            $model->attributes=$_POST['User'];
			if($model->save())
            {       
                $orgs = isset($_POST['User']['organizations']) ? $_POST['User']['organizations'] : array();
                $model->saveRelationOrganizations($orgs, $model->id, $model->role_admin);
				$this->redirect(array('profile/create', 'id'=>$model->id));				
            }
		}
		else
		{
			$model->default_organization = Yii::app()->session['organization'];
			$model->organizations = array($model->default_organization => $model->default_organization);
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
		$model=$this->loadModel($id);
        
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			
			if($model->save())
            {
                $orgs = isset($_POST['User']['organizations']) ? $_POST['User']['organizations'] : array();                
                $model->saveRelationOrganizations($orgs, $model->id, $model->role_admin);
                $this->redirect(array('view','id'=>$model->id));
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
	 * @throws CHttpException
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{			
			$model=$this->loadModel($id);			
			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 * @see User
	 */
	public function actionAdmin()
	{	
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];
        
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 * @throws CHttpException
	 * @uses self::actionView()
	 * @uses self::actionUpdate()
	 * @uses self::actionDelete()
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 * @deprecated
	 */
	protected function performAjaxValidation($model)
	{
	    throw new CHttpException(410);
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    
    
    
    
    
}
