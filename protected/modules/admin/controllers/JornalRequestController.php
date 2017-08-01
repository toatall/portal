<?php

class JornalRequestController extends AdminController
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
	public function actionView($id,$idTree)
	{
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
		
        if (!Tree::model()->exists('id=:id AND module=:module', array(':id'=>$idTree,'module'=>'jornalRequest')))
            throw new CHttpException(404,'Страница не найдена.');
        
        if (!(Yii::app()->user->admin || Access::model()->checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
        
        $model=new JornalRequest;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['JornalRequest']))
		{
			$model->attributes=$_POST['JornalRequest'];
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
		$model=$this->loadModel($id,$idTree);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['JornalRequest']))
		{
			$model->attributes=$_POST['JornalRequest'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id,'idTree'=>$idTree));
		}

		$this->render('update',array(
			'model'=>$model,
            'idTree'=>$idTree,
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
	public function actionAdmin($idTree)
	{
	   
        if (!Tree::model()->exists('id=:id AND module=:module', array(':id'=>$idTree,'module'=>'jornalRequest')))
            throw new CHttpException(404,'Страница не найдена.');
        
        if (!(Yii::app()->user->admin || Access::model()->checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
            
		$model=new JornalRequest('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['JornalRequest']))
			$model->attributes=$_GET['JornalRequest'];

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
		if (!Tree::model()->exists('id=:id AND module=:module', array(':id'=>$idTree,'module'=>'jornalRequest')))
            throw new CHttpException(404,'Страница не найдена.');
        
        if (!(Yii::app()->user->admin || Access::model()->checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
            
        $model=JornalRequest::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='jornal-request-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
