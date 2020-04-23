<?php

class AnswerController extends Controller
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
	public function actionCreate($idQuestion)
	{
		$model=new TestAnswer;
        $modelQuestion = $this->loadModelQuestion($idQuestion);
        $model->id_test_question = $idQuestion;
        $model->weight = 0;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TestAnswer']))
		{
			$model->attributes=$_POST['TestAnswer'];
			if($model->save()) {
			    $model->saveFile();
                $this->redirect(['/test/answer/admin', 'idQuestion'=>$idQuestion]);
            }
		}

		$this->render('create',array(
			'model' => $model,
            'modelQuestion' => $modelQuestion,
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

		if(isset($_POST['TestAnswer']))
		{
			$model->attributes=$_POST['TestAnswer'];
			if($model->save()) {
                $model->saveFile();
                $this->redirect(['/test/answer/admin', 'idQuestion'=>$model->id_test_question]);
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
	    $model = $this->loadModel($id);
	    $idQuestion = $model->id_test_question;
		$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) {
            $this->redirect(array('admin', 'idQuestion'=>$idQuestion));
        }
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($idQuestion)
	{
	    $modelQuestion = $this->loadModelQuestion($idQuestion);
		$model=new TestAnswer('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TestAnswer'])) {
            $model->attributes = $_GET['TestAnswer'];
        }

        $model->id_test_question = $idQuestion;
		$this->render('admin',array(
			'model'=>$model,
            'modelQuestion'=>$modelQuestion,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TestAnswer the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TestAnswer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    /**
     * Returns the data model TestQuestion
     * @param $id
     * @return array|mixed|null
     * @throws CHttpException
     */
	public function loadModelQuestion($id)
    {
        $model=TestQuestion::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

	/**
	 * Performs the AJAX validation.
	 * @param TestAnswer $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='test-answer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * @return mixed
     */
    protected function isManager()
    {
        return Yii::app()->user->inRole(['admin']);
    }
}
