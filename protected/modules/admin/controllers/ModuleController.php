<?php

/**
 * Mange modules
 * @author alexeevich
 * @see AdminController
 * @see Module
 */
class ModuleController extends AdminController
{	

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
	 * @see Module
	 * @see Access
	 */
	public function actionCreate()
	{
		$model=new Module;

		if(isset($_POST['Module']))
		{
			$model->attributes=$_POST['Module'];
			if($model->save())
			{
				$permissionGroups = (isset($_POST['Module']['permissionGroup']))
					? $_POST['Module']['permissionGroup'] : array();
				$permissionUsers = (isset($_POST['Module']['permissionUser']))
					? $_POST['Module']['permissionUser'] : array();
				
				//  сохранение прав доступа к модулю
				Access::saveRelationsPermissionModule($model,
					$permissionGroups, $permissionUsers);
				
				$this->redirect(array('view','id'=>$model->name));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 * @see Access
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Module']))
		{
			$model->attributes=$_POST['Module'];
			if($model->save())
			{
				$permissionGroups = (isset($_POST['Module']['permissionGroup']))
					? $_POST['Module']['permissionGroup'] : array();
				$permissionUsers = (isset($_POST['Module']['permissionUser']))
					? $_POST['Module']['permissionUser'] : array();
				
                //  сохранение прав доступа к модулю
				Access::saveRelationsPermissionModule($model,
					$permissionGroups, $permissionUsers);
				
				$this->redirect(array('view','id'=>$model->name));
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
		$dataProvider=new CActiveDataProvider('Module');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 * @see Module
	 */
	public function actionAdmin()
	{
		$model=new Module('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Module']))
			$model->attributes=$_GET['Module'];

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
	 */
	public function loadModel($id)
	{
		$model=Module::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='module-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
