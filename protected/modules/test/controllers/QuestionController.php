<?php

class QuestionController extends Controller
{
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
            array('allow',
                'expression'=>function() {
                    return $this->isManager();
                },
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     * @throws CHttpException
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
	public function actionCreate($idTest)
	{
		$model=new TestQuestion;
		$model->id_test = $idTest;
		$model->weight = 1;
        $modelTest = $this->loadModelTest($idTest);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TestQuestion']))
		{
			$model->attributes=$_POST['TestQuestion'];
			if($model->save()) {
			    $model->saveFile();
                $this->redirect(array('view', 'id' => $model->id));
            }
		}

		$this->render('create',array(
			'model'=>$model,
            'modelTest'=>$modelTest,
		));
	}

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     * @throws CHttpException
     */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TestQuestion']))
		{
			$model->attributes=$_POST['TestQuestion'];
			if($model->save()) {
			    $model->saveFile();
                $this->redirect(array('view', 'id' => $model->id));
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
     * @throws CDbException
     * @throws CHttpException
     */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($idTest)
	{
	    $modelTest = $this->loadModelTest($idTest);
		$model=new TestQuestion('search');
		$model->unsetAttributes();  // clear any default values
        $model->id_test = $idTest;
		if(isset($_GET['TestQuestion'])) {
            $model->attributes = $_GET['TestQuestion'];
        }

		$this->render('admin',array(
			'model'=>$model,
            'modelTest'=>$modelTest,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TestQuestion the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TestQuestion::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    /**
     * @param $id
     * @return Test
     * @throws CHttpException
     */
	public function loadModelTest($id)
    {
        $model = Test::model()->findByPk($id);
        if ($model === null)
        {
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

	/**
	 * Performs the AJAX validation.
	 * @param TestQuestion $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='test-question-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * Права администратора
     * @return mixed
     */
    protected function isManager()
    {
        return Yii::app()->user->inRole(['admin']);
    }

}
