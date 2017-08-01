<?php

class DepartmentDataController extends AdminController
{

	private $_modelDepartment;
	
	// действие по-умолчанию
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
	 * Проверка существования раздела Tree и прав у пользователя на него
	 * @param int $idDepartment
	 * @throws CHttpException
	 */
	private function checkDepartmentRight($idTree)
	{
		$this->_modelDepartment = Department::model()->find('id_tree=:id_tree', array(':id_tree'=>$idTree));
		if ($this->_modelDepartment == null)
			throw new CHttpException(404,'Страница не найдена!');
		
		if (!$this->checkAccessUserByDepartment($idTree))
			throw new CHttpException(403,'У вас недостаточно прав для выполнения указанного действия!');		
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id, $idTree)
	{
		
		$this->checkDepartmentRight($idTree);
		
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'modelDepartment'=>$this->_modelDepartment,
				
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($idTree)
	{
		$this->checkDepartmentRight($idTree);
		
		$model=new NewsSearch();
		$model->date_start_pub = date('d.m.Y');
		$model->date_end_pub = '01.01.2032';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['News']))
		{
			$model->attributes=$_POST['News'];
			$model->log_change = LogChange::setLog($model->log_change, 'создание');
			$model->id_tree = $idTree;
			if($model->save())
			{	
				// сохраняем файлы
				$model->saveFiles($model->id, $idTree);
				// сохраняем изображения
				$model->saveImages($model->id, $idTree);
				
				$this->redirect(array('view','id'=>$model->id, 'idTree'=>$idTree));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'modelDepartment'=>$this->_modelDepartment,
			'modelTree'=>Tree::model()->findByPk($idTree),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $idTree)
	{
		
		$this->checkDepartmentRight($idTree);
		
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['News']))
		{
			$model->attributes=$_POST['News'];
			$model->log_change = LogChange::setLog($model->log_change, 'изменение');
			if($model->save())
			{	
				// файлы для удаления
				if (isset($_POST['News']['deleteFile']))
					{ $delFile = $_POST['News']['deleteFile']; }
						else { $delFile = array(); }
				 
				// изображения для удаления
				if (isset($_POST['News']['deleteImage']))
					{ $delImage = $_POST['News']['deleteImage']; }
						else { $delImage = array(); }
				
				// сначала удаляем файлы и изображения помеченные для удаления
				$model->deleteFilesImages($model->id, $delFile, $delImage, $idTree);				
				// сохраняем файлы
				$model->saveFiles($model->id, $idTree);
				// сохраняем изображения
				$model->saveImages($model->id, $idTree);
				
				$this->redirect(array('view','id'=>$model->id, 'idTree'=>$idTree));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'modelDepartment'=>$this->_modelDepartment,
			'modelTree'=>Tree::model()->findByPk($idTree),
		));
	}
	

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id, $idTree)
	{
		
		$this->checkDepartmentRight($idTree);
		
		if(Yii::app()->request->isPostRequest)
		{
			if (!Yii::app()->user->admin)
			{
				$model = $this->loadModel($id, $idTree);
				$model->date_delete = new CDbExpression('getdate()');
				$model->log_change = LogChange::setLog($model->log_change,'удаление');
				$model->save();
			}
			else
			{
				// we only allow deletion via POST request
				$this->loadModel($id, $idTree)->delete();
		
				// удаляем все файлы и изображения
				News::model()->DeleteFilesImages($id,
						CHtml::listData(Yii::app()->db->createCommand(array(
								'select'=>'id',
								'from'=>'{{file}}',
								'where'=>'id_model=:id_model AND model=:model',
								'params'=>array(':id_model'=>$id, ':model'=>'news'),
						))->queryAll(),
								'id','id'),
						CHtml::listData(Yii::app()->db->createCommand(array(
								'select'=>'id',
								'from'=>'{{image}}',
								'where'=>'id_model=:id_model AND model=:model',
								'params'=>array(':id_model'=>$id, ':model'=>'news'),
						))->queryAll(),
								'id','id'),
						$idTree
				);
			}
		
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'idTree'=>$idTree));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
				
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin($idTree)
	{
		
		$this->checkDepartmentRight($idTree);
		
		
		$model = new NewsSearch('search');
		$model->unsetAttributes();  // clear any default values
		$model->id_tree = $idTree;
			
		if(isset($_GET['News']))
			$model->attributes=$_GET['News'];

		$this->render('admin',array(
			'model'=>$model,
			'modelDepartment'=>$this->_modelDepartment,
		));
	}
	
	
	public function actionOptions($id, $idTree)
	{
		$this->checkDepartmentRight($idTree);
		
		$model = Department::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		if(isset($_POST['Department']))
		{
			$model->attributes=$_POST['Department'];
			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Настройки успешно сохранены!');
			}
			else 
			{
				Yii::app()->user->setFlash('error', 'Не удалось сохранить настройки!');
			}
		}
		
		$this->render('options', array(
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
		$model=News::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	
	private function loadModelByUser($id)
	{
		if (!$this->checkAccessUserByDepartment($id))
			throw new CHttpException(403,'У вас недостаточно прав для выполнения указанного действия.');
		return $this->loadModel($id);		
	}
	
	private function checkAccessUserByDepartment($id)
	{
		if (Yii::app()->user->admin) return true;
		
		return Yii::app()->db->createCommand()			
			->from('{{department}} d')
			->leftJoin('{{access_department_user}} access_user', 'd.id = access_user.id_department')
			->leftJoin('{{access_department_group}} access_group', 'd.id = access_group.id_department')
			->leftJoin('{{group_user}} group_user', 'access_group.id_group = group_user.id_group')
			->where('group_user.id_user=:user1 or access_user.id_user=:user2', 
				[':user1'=>Yii::app()->user->id,':user2'=>Yii::app()->user->id])
			->queryRow();
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
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
