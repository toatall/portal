<?php

class TestController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('admin','view'),
				'users'=>array('@'),
			),
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
	public function actionCreate()
	{
	    /* @var $dateHelper DateHelper */
	    $dateHelper = Yii::app()->dateHelper;
		$model=new Test;
		$model->count_attempt = 0;
		$model->count_questions = 0;
		$model->date_start = $dateHelper->asDate();
		$model->date_end = $dateHelper->maxDate();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Test']))
		{
			$model->attributes=$_POST['Test'];
			if($model->save()) {
			    return $this->redirect(['/test/question/create', 'idTest'=>$model->id]);
            }
		}

		return $this->render('create', [
		    'model' => $model,
        ]);
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

		if(isset($_POST['Test']))
		{
			$model->attributes=$_POST['Test'];
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
	 * Lists all models.
	 */
	public function actionIndex()
	{
	    $modelSearch = new TestSearch();
		$this->render('index',array(
			'dataProvider'=>$modelSearch->searchTest(),
		));
	}

    /**
     * Сдача теста
     * @param $id
     * @throws CException
     * @throws CHttpException
     */
	public function actionStart($id)
    {
        /**
         * Логика по загрузке теста, вопросов и ответов
         * 0. Если прилетели post данные, то сохранить их и сказать спасибо!
         * 1. Загрузить тест в соотвествии с идентификатором, если время активности закончилось, то вывести информацию, тест закрыт
         * 2. Проверить сдавал ли пользователь ранее и можно ли еще сдавать
         * 3. Загрузить вопросы, ответы и передать массивом
         */


        $model = $this->loadModel($id);
        // Если тест уже закончился, то показать об этом информацию
        if (!$model->getActive())
        {
            echo CJavaScript::jsonEncode([
                'title' => $model->name,
                'content' => $this->renderPartial('_message', [
                    'message' => 'Тестирование завершено!',
                ], true),
            ]);
            Yii::app()->end();
        }
        // Если ограничено количество попыток и пользователь уже истратил все свои попытки
        $countUserAttempts = TestResult::countUserAttempts($model->id);
        if ($model->count_attempt > 0 && $countUserAttempts >= $model->count_attempt)
        {
            echo CJavaScript::jsonEncode([
                'title' => $model->name,
                'content' => $this->renderPartial('_message', [
                    'message' => 'Ваши попытки сдать тест закончились!',
                ], true),
            ]);
            Yii::app()->end();
        }

        if (isset($_POST['Test']))
        {
            // сохранение теста
            try {

                $modelResult = new TestResult();
                $result = $modelResult->saveResult($_POST['Test']);
                $this->renderPartial('_result', [
                    'result' => $result,
                ]);

            } catch (Exception $exception) {

                $this->renderPartial('_message', [
                    'typeMessage' => 'alert-danger',
                    'message' => 'Во время сохранения произошли ошибки!<br />' . $exception->getMessage() . print_r($exception->getTrace(), true),
                ]);

            }
            Yii::app()->end();
        }

        $modelSearch = new TestSearch();
        echo CJavaScript::jsonEncode([
            'title' => $model->name,
            'content' => $this->renderPartial('start', [
                'model' => $model,
                'testData' => $modelSearch->searchTestData($model->id),
                'attempts' => [
                    'model' => $model->count_attempt,
                    'user' => $countUserAttempts,
                ],
            ], true),
        ]);
    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Test('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Test']))
			$model->attributes=$_GET['Test'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Test the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Test::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	/**
	 * Performs the AJAX validation.
	 * @param Test $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='test-form')
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
