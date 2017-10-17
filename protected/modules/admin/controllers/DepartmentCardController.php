<?php

class DepartmentCardController extends AdminController
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
	public function actionCreate($idDepartment)
	{
	    
	    $this->loadModelByUser($idDepartment);
	    
		$model=new DepartmentCard;
		$model->id_department = $idDepartment;		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DepartmentCard']))
		{
		    
			$model->attributes=$_POST['DepartmentCard'];
			if($model->save())
			{				
				$model->loadFilePhoto($model);
				$this->redirect(array('department/updateStructure','id'=>$model->id_department));
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
        
		$this->loadModelByUser($model->id_department);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DepartmentCard']))
		{
			$model->attributes=$_POST['DepartmentCard'];
			if($model->save())
			{			
				$model->loadFilePhoto($model);
				$this->redirect(array('department/updateStructure','id'=>$model->id_department));
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
	    if (Yii::app()->user->inRole('admin'))
	        throw new CHttpException(403,'У вас недостаточно прав для выполнения указанного действия.');
	    
		if(Yii::app()->request->isPostRequest)
		{
		    $model = $this->loadModel($id);		    
			// we only allow deletion via POST request
			$model->delete();

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
		$dataProvider=new CActiveDataProvider('DepartmentCard');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DepartmentCard('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DepartmentCard']))
			$model->attributes=$_GET['DepartmentCard'];

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
		$model=DepartmentCard::model()->with('department')->findByPk($id, 
			'department.id_organization=:id_org', [':id_org'=>Yii::app()->session['organization']]);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='department-card-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	private function loadModelByUser($id)
	{
	    if (!Department::checkAccessUser($id))
	        throw new CHttpException(403,'У вас недостаточно прав для выполнения указанного действия.');	        
	}
	
	
	
}
