<?php

/**
 * Управление отделами
 * @author alexeevich
 * @see AdminController
 * @see Department
 */
class DepartmentController extends AdminController
{

	/**
	 * Default action
	 * @var string
	 */
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
	 * @see Department
	 */
	public function actionCreate()
	{
		$model=new Department;
		$model->id_organization = Yii::app()->session['organization'];
		
		if(isset($_POST['Department']))
		{
			$model->attributes=$_POST['Department'];
			if($model->save())
			{
				$this->saveRelations($model);				
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

		if(isset($_POST['Department']))
		{
			$model->attributes=$_POST['Department'];
			if($model->save())
			{
				$this->saveRelations($model);			
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Структура отдела 
	 * @desc
	 * 1. Карточка отдела - представляет собой
	 * сотрудников отдела с информацией о них
	 * (ФИО, телефон, должность, чин, фотография)
	 * 2. Список ссылок на ветки структуры 
	 * (структура доступная пользователю)
	 * Добавить возможность выбрать значок (иконку)
	 * @param int $id идентификатор отдела
	 * @see DepartmentCard
	 */
	public function actionUpdateStructure($id)
	{
		$modelCard=new DepartmentCard('search');
		$modelCard->unsetAttributes();  // clear any default values
		if(isset($_GET['DepartmentCard']))
			$modelCard->attributes=$_GET['DepartmentCard'];
				
		$model = $this->loadModelByUser($id);
		$this->render('updateStructure', [
			'model' => $model,
			'modelCard' => new DepartmentCard(), //$modelCard,
		]);
	}
	
	
	/**
	 * Действие по отображению отделов в соотвествии 
	 * с правами пользователя
	 */
	public function actionIndex()
	{
		$model=new Department('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Department']))
			$model->attributes=$_GET['Department'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	
	/**
	 * Сохранение пользователей и групп доступа к отделу
	 * @param Department $model
	 * @uses self::actionCreate()
	 * @uses self::actionUpdate()
	 */
	private function saveRelations($model)
	{
		$permissionGroups = (isset($_POST['Department']['permissionGroup']))
			? $_POST['Department']['permissionGroup'] : array();
		$permissionUsers = (isset($_POST['Department']['permissionUser']))
			? $_POST['Department']['permissionUser'] : array();
		
		Access::saveRelationsPermissionsDepartment($model, $permissionGroups, $permissionUsers);
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
		$model=new Department('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Department']))
			$model->attributes=$_GET['Department'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 * @uses self::actionView()
	 */
	public function loadModel($id)
	{
		$model=Department::model()->findByPk($id, 'id_organization=:id_organization', 
				[':id_organization'=>Yii::app()->session['organization']]);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * Поиск отдел по идентификатору $id
	 * @param int $id идентификатор отдела
	 * @throws CHttpException
	 * @return Department
	 * @uses self::actionUpdateStructure()
	 */
	private function loadModelByUser($id)
	{
		if (!$this->checkAccessUserByDepartment($id))
			throw new CHttpException(403,'У вас недостаточно прав для выполнения указанного действия.');
		return $this->loadModel($id);		
	}
	
	/**
	 * Проаверка прав пользователя к отделу
	 * @param int $id идентификатор отдела
	 * @return bool
	 * @uses self::loadModelByUser()
	 */
	private function checkAccessUserByDepartment($id)
	{
		if (Yii::app()->user->admin) return true;
		
		return Yii::app()->db->createCommand()	
		    ->select('count(*)')
			->from('{{department}} d')
			->leftJoin('{{access_department_user}} access_user', 'd.id = access_user.id_department')
			->leftJoin('{{access_department_group}} access_group', 'd.id = access_group.id_department')
			->leftJoin('{{group_user}} group_user', 'access_group.id_group = group_user.id_group')
			->where('group_user.id_user=:user1 or access_user.id_user=:user2', 
				[':user1'=>Yii::app()->user->id,':user2'=>Yii::app()->user->id])
			->queryScalar();
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 * @deprecated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='department-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
		
}
