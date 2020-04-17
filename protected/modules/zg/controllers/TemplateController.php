<?php

class TemplateController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
            array('allow',
                'actions'=>array('create', 'update', 'delete'),
                'expression'=>function() {
                    return $this->isEditor();
                },
            ),
            array('allow',
                'expression'=>function() {
                    return Yii::app()->user->inRole(['admin']);
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
     * @throws CException
     * @throws CHttpException
     */
	public function actionView($id)
	{
	    $model = $this->loadModel($id);
        echo CJSON::encode([
            'title' => $model->kind,
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
		$model=new Template();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Template']))
		{
			$model->attributes=$_POST['Template'];
			if($model->save())
            {
                $model->saveFiles();
                return $this->redirect('/zg/template/index');
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
     * @return string|void
     * @throws CException
     * @throws CHttpException
     */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Template']))
		{
            $model->attributes = $_POST['Template'];
            if ($model->save())
            {
                if ($model->deleteFile)
                {
                    $model->deleteFiles($model->deleteFile);
                }
                $model->saveFiles();
                return $this->redirect('/zg/template/index');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);

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
		$model=new Template('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Template']))
			$model->attributes=$_GET['Template'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer $id the ID of the model to be loaded
	* @return Template the loaded model
	* @throws CHttpException
	*/
	public function loadModel($id)
	{
		$model = Template::model()->findByPk($id);
		if($model === null)
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

    /**
     * Есть ли права на редактирование
     * @return bool
     */
	public function isEditor()
    {
        // если права администратора
        if (Yii::app()->user->admin) {
            return true;
        }

        $accounts = Yii::app()->params['zg']['template']['editAccounts'];

        // поиск по имени учетной записи
        if (in_array(Yii::app()->user->name, $accounts)) {
            return true;
        }

        // поиск по группам
        foreach ($accounts as $account) {
            if (in_array($account, UserInfo::inst()->ADMemberOf)) {
                return true;
            }
        }

        return false;
    }

}