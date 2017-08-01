<?php

class DefaultController extends Controller
{
	
	
	// действие по-умолчанию
	public $defaultAction = 'admin';
	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions' => array('admin',  'view'),
				'users'=>array('@'),
			),
			
			array('allow',
				'actions' => array('create','update'),
				'expression'=>function() { return Yii::app()->user->inRole(['admin', 'reestrSVT_UFNS', 'reestrSVT_IFNS']); },
			),
			
			array('allow',
				'actions' => array('appearFku', 'requestUFNS'),
				'expression'=> function() { return Yii::app()->user->inRole(['admin', 'reestrSVT_FKU']); },
			),
						
			array('allow',
				'actions' => array('appearUFNS'),
				'expression'=> function() { return Yii::app()->user->inRole(['admin', 'reestrSVT_UFNS']); },
			),
			
			array('allow',
				'actions' => array('close'),
				'expression'=>function() { return Yii::app()->user->inRole(['admin', 'reestrSVT_UFNS', 'reestrSVT_IFNS', 'reestrSVT_FKU']); },
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
		$model=new ReestrSvt;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ReestrSvt']))
		{
			$model->attributes=$_POST['ReestrSvt'];
			$model->message = $model->fault_description;
			$model->status_code = 1; // направлена
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	
	/**
	 * Утверждение заявки ФКУ
	 * @param int $id
	 */
	public function actionAppearFKU($id)
	{
		$model=$this->loadModel($id);
		$model->date_acceptance_fku = new CDbExpression('getdate()');
		$model->save();		
		$this->redirect(['view', 'id'=>$id]);
	}
	
	
	/**
	 * Направление заявки в УФНС
	 * @param int $id
	 */
	public function actionRequestUFNS($id)
	{	
		$model=$this->loadModel($id);
		$model->scenario = 'requestUFNS';
		if(isset($_POST['ReestrSvt']))
		{
			$model->attributes=$_POST['ReestrSvt'];
			$model->date_appeal_fku_ufns = new CDbExpression('getdate()');
			$model->solved_fku = false;
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->renderPartial('_modalRequestToUFNS', [
			'model'=>$model,
		], false, true);
	}
	
	
	
	/**
	 * Утверждение заявки УФНС
	 * @param int $id
	 */
	public function actionAppearUFNS($id)
	{
		$model=$this->loadModel($id);
		$model->date_acceptance_ufns = new CDbExpression('getdate()');
		$model->save();
		$this->redirect(['view', 'id'=>$id]);
	}
	
	
	
	/**
	 * Закрытие заявки
	 * @param int $id
	 */
	public function actionClose($id)
	{		
		$model=$this->loadModel($id);
		
		if (Yii::app()->user->inRole(['reestrSVT_FKU']))
			$model->solved_fku = true;
		
		$model->scenario = 'requestClose';
		if(isset($_POST['ReestrSvt']))
		{
			$model->attributes=$_POST['ReestrSvt'];
			$model->date_close = new CDbExpression('getdate()');
				
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
	
		$this->renderPartial('_modalRequestClose', [
			'model'=>$model,
		], false, true);
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

		if(isset($_POST['ReestrSvt']))
		{
			$model->attributes=$_POST['ReestrSvt'];
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
		$model=new ReestrSvt('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ReestrSvt']))
			$model->attributes=$_GET['ReestrSvt'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ReestrSvt::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='reestr-svt-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
