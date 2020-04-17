<?php

class EmailGovermentController extends Controller
{

	/**
	* @return array action filters
	*/
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update','delete'),
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
	    $model = $this->loadModel($id);
        echo CJSON::encode([
            'title' => $model->org_name,
            'content' => $this->renderPartial('view', [
                'model' => $model,
            ], true, true),
        ]);
	}

	/**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionCreate()
	{
		$model=new EmailGoverment;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['EmailGoverment']))
		{
			$model->attributes=$_POST['EmailGoverment'];
			if($model->save())
            {

                echo CJavaScript::jsonEncode([
                    'title' => $model->org_name,
                    'content' => $this->renderPartial('success', [],true),
                ]);
                Yii::app()->end();
            }
		}


        echo CJavaScript::jsonEncode([
            'title' => 'Создание адреса',
            'content' => $this->renderPartial('create', [
                'model' => $model,
            ], true),
        ]);

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

		if(isset($_POST['EmailGoverment'])) {
            $model->attributes = $_POST['EmailGoverment'];
            if ($model->save()) {
                echo CJavaScript::jsonEncode([
                    'title' => $model->org_name,
                    'content' => $this->renderPartial('success', [], true),
                ]);
                Yii::app()->end();
            }
        }

        echo CJavaScript::jsonEncode([
            'title' => $model->org_name,
            'content' => $this->renderPartial('update', [
                'model' => $model,
            ], true),
        ]);

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
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}



	/**
	* Manages all models.
	*/
	public function actionIndex()
	{
		$model=new EmailGoverment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['EmailGoverment']))
			$model->attributes=$_GET['EmailGoverment'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer $id the ID of the model to be loaded
	* @return EmailGoverment the loaded model
	* @throws CHttpException
	*/
	public function loadModel($id)
	{
		$model=EmailGoverment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param EmailGoverment $model the model to be validated
	*/
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='email-goverment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}