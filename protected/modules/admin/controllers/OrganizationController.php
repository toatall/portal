<?php

/**
 * Organizations manage
 * @author alexeevich
 * @see AdminController
 * @see Organization
 */
class OrganizationController extends AdminController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */	

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
	 * @param string $code the ID of the model to be displayed
	 */
	public function actionView($code)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($code),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @see Organization
	 */
	public function actionCreate()
	{
		$model=new Organization;
		
		if(isset($_POST['Organization']))
		{
			$model->attributes=$_POST['Organization'];
			if($model->save())
				$this->redirect(array('view','code'=>$model->code));
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
		
		if(isset($_POST['Organization']))
		{
			$model->attributes=$_POST['Organization'];
			if($model->save())
				$this->redirect(array('view','code'=>$model->code));
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
	 * Lists all models.
	 * @return CActiveDataProvider
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Organization');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 * @see Organization
	 */
	public function actionAdmin()
	{
		$model=new Organization('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Organization']))
			$model->attributes=$_GET['Organization'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param string $code the ID of the model to be loaded
	 * @see Organization
	 * @throws CHttpException
	 * @return Organization
	 * @uses self::actionView()
	 * @uses self::actionUpdate()
	 * @uses self::actionDelete()
	 */
	public function loadModel($code)
	{
		$model=Organization::model()->findByPk($code);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='organization-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
