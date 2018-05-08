<?php

/**
 * Manage Vks Fns
 * @author alexeevich
 * @see AdminController
 */
class VksFnsController extends AdminController
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
				'users'=>array('@'),
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
		$this->render('/conference/view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @see Conference
	 */
	public function actionCreate()
	{
		$model=new Conference();
		$model->_tempDateStart = date('d.m.Y');
		$model->_tempTimeStart = date('H') . ':00';
		$model->duration = '01:00';
		$model->time_start_msk = true;
		$model->type_conference = Conference::TYPE_VKS_FNS;
		$this->checkAccess($model);
	
		if(isset($_POST['Conference']))
		{
			$model->attributes=$_POST['Conference'];
			if($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}
	
		$this->render('/conference/create',array(
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
		
		if(isset($_POST['Conference']))
		{
			$model->attributes=$_POST['Conference'];			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
	
		$this->render('/conference/update',array(
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
			if (!Yii::app()->user->admin)
			{
				$model = $this->loadModel($id);
				$model->date_delete = new CDbExpression('getdate()');
				$model->save();
			}
			else
			{
				// we only allow deletion via POST request
				$this->loadModel($id)->delete();
			}
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin','idTree'=>$idTree));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * Проверка прав пользователя
	 * @throws CHttpException
	 * @see Access
	 * @uses self::actionCreate()
	 * @uses self::loadModel()
	 */
	private function checkAccess($model)
	{
		$idTree = $model->treeId;
		if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree)))
			throw new CHttpException(403,'Доступ запрещен.');
	}
	
	/**
	 * Manages all models.
	 * @see Conference
	 */
	public function actionAdmin()
	{
		$model=new Conference('search');
		$model->unsetAttributes();  // clear any default values
		$model->type_conference = Conference::TYPE_VKS_FNS;
		$this->checkAccess($model);
		
		if(isset($_GET['Conference']))
			$model->attributes=$_GET['Conference'];
	
		$this->render('/conference/admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 * @see Conference
	 * @uses self::actionView()
	 * @uses self::actionDelete()
	 */
	public function loadModel($id)
	{
		$model = Conference::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		$this->checkAccess($model);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='vks-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
