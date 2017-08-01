<?php

class RatingDataController extends AdminController
{
	
	public $defaultAction = 'admin';

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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($idTree)
	{
		$model=new RatingMain;
		$modelTree = $this->loadModelTree($idTree);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RatingMain']))
		{
			$model->attributes=$_POST['RatingMain'];
			$model->id_tree = $modelTree->id;
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'modelTree'=>$modelTree,
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

		if(isset($_POST['RatingMain']))
		{
			$model->attributes=$_POST['RatingMain'];
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
	public function actionAdmin($idTree)
	{
		$model=new RatingMain('search');
		$model->unsetAttributes();  // clear any default values
		$model->id_tree = $idTree;
		if(isset($_GET['RatingMain']))
			$model->attributes=$_GET['RatingMain'];

		$this->render('admin',array(
			'model'=>$model,
			'modelTree'=>$this->loadModelTree($idTree),
		));
	}
	
	
	
	
	public function actionAdminRating($id)
	{
		$modelRatingMain = $this->loadModelRatingMain($id);
		$model=new RatingData('search');
		$model->unsetAttributes();  // clear any default values
		$model->id_rating_main = $id;
		if(isset($_GET['RatingData']))
		{	
			$model->attributes=$_GET['RatingData'];
		}
		
		$this->render('adminRating',array(
			'model'=>$model,
			'modelRatingMain'=>$modelRatingMain,
		));
	}
	
	
	
	public function actionCreateRating($id)
	{		
		$model=new RatingData;
		$modelRatingMain = $this->loadModelRatingMain($id);
		$model->rating_year = date('Y');
		$model->rating_period = date('m') . '_1_mes';
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['RatingData']))
		{
			$model->attributes=$_POST['RatingData'];
			$model->id_rating_main = $modelRatingMain->id;
				
			if($model->save())
			{
				$model->saveFiles(); // @todo if not save files?
				$this->redirect(array('viewRating','id'=>$model->id));
			}
		}
		
		$this->render('createRating',array(
			'model'=>$model,
			'modelRatingMain'=>$modelRatingMain,
		));
	}
	
	
		
	public function actionUpdateRating($id)
	{
		$model=$this->loadModelRatingData($id);
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		if(isset($_POST['RatingData']))
		{
			$model->attributes=$_POST['RatingData'];
			if($model->save())
			{
				$model->saveFiles();
				
				// файлы для удаления
				$delFile = array();
				if (isset($_POST['RatingFile']['deleteFile']))
				{ 					
					foreach ($_POST['RatingFile']['deleteFile'] as $f)
					{
						$delFile[$f] = $f;
					}
				}				
				$model->deleteFiles($delFile);
				
				$this->redirect(array('viewRating','id'=>$model->id));
			}			
		}
	
		$this->render('updateRating',array(
			'model'=>$model,
		));
	}
	
	
	
	
	public function actionViewRating($id)
	{
		$this->render('viewRating',array(
			'model'=>$this->loadModelRatingData($id),
		));
	}
	
	
	
	public function actionDeleteRating($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model = $this->loadModelRatingData($id);
			$id = $model->id_rating_main;
			// we only allow deletion via POST request
			$model->delete();
	
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('adminRating','id'=>$id));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=RatingMain::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	
	/**
	 * Load model for Tree
	 * @param int $id
	 * @throws CHttpException
	 * @return NULL|unknown|unknown[]|NULL[]|mixed
	 */
	public function loadModelTree($id)
	{
		$model=Tree::model()->findByPk($id, 'id_organization=:org', [':org'=>Yii::app()->session['organization']]);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	
	/**
	 * Load model for RatingData
	 * @param unknown $id
	 */
	public function loadModelRatingMain($id)
	{
		$model=RatingMain::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	
	public function loadModelRatingData($id)
	{		
		$model=RatingData::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if (!Tree::checkTreeNode($model->ratingMain->id_tree))
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='rating-main-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
