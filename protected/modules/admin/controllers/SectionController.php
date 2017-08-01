<?php

class SectionController extends AdminController
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
                'expression'=>'SectionController::allowSection()',
            ),
            array('deny',  // deny all users
				'users'=>array('*'),
			),			            
		);
	}
    
    /** выражение, для проверки доступа пользователя к разделу */
    public static function allowSection()
    {
        if (!isset($_GET['id_tree']))
            throw new CHttpException(400, "Неверный запрос. Не указан id_tree-параметр!");
        if (!is_numeric($_GET['id_tree']))
            throw new CHttpException(400, "Неверный запрос. Ошибочное значение id_tree-параметра!");        
        $id = $_GET['id_tree'];
        if (!Tree::model()->exists('id=:id', array(':id'=>$id)))
            throw new CHttpException(400, "Неверный запрос. Раздел с ИД $id не найден!");
        return (($model = Yii::app()->db->createCommand("select count(*) as res_access from p_func_tree_node_access($id,".Yii::app()->user->id.")")->queryAll())
            && $model[0]['res_access']);
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
		$model=new Section;
        

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Section']))
		{
			$model->attributes=$_POST['Section'];
			if($model->save())
            {
                $orgs = (isset($_POST['Section']['Organization'])) ? $_POST['Section']['Organization'] : array();
                $model->saveRelationOrganizations($orgs, $model->id);
				$this->redirect(array('view','id'=>$model->id));
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
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Section']))
		{
			$model->attributes=$_POST['Section'];
			if($model->save())
            {
                $orgs = (isset($_POST['Section']['Organization']) && $model->use_organization) 
                    ? $_POST['Section']['Organization'] : array();
                $model->saveRelationOrganizations($orgs, $model->id);
				$this->redirect(array('view','id'=>$model->id));
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
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Section');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($id_tree)
	{
        if (!Tree::model()->exists('id=:id', array(':id'=>$id_tree)))
            throw new CHttpException(400, "Неверный запрос. Раздел с ИД $id_tree не найден!");
        if (!CheckAcccess::checkAccessNode($id_tree))
            throw new CHttpException(403, "У вас недостаточно прав для выполнения указанного действия.");
                
		$model=new Section('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Section']))
			$model->attributes=$_GET['Section'];

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
		$model=Section::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='section-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
