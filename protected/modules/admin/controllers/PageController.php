<?php

class PageController extends AdminController
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
                'users'=>array('@'),//User::model()->getAdminsName(),
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
	public function actionView($id, $idTree)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id, $idTree),
            'modelTree'=>$this->loadModelTree($idTree),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($idTree)
	{        
        
        $modelTree = $this->loadModelTree($idTree);
		
        if (!(Yii::app()->user->admin || Access::model()->checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
            
        $model=new News;
        
        $model->id_tree = $idTree;
        $model->flag_enable = true;
        $model->date_start_pub = date('d.m.Y');
        $model->date_end_pub = date('01.m.Y', PHP_INT_MAX);
        $model->author = Yii::app()->user->name;
        $model->general_page=0; 
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['News']))
		{
		    $model->log_change = LogChange::setLog($model->log_change, 'создание');
			$model->attributes=$_POST['News'];
			
			if($model->save()) {
			    
                // сохраняем файлы                
                $model->saveFiles($model->id, $idTree);
                // сохраняем изображения
                $model->SaveImages($model->id, $idTree);
                
                $this->redirect(array('view','id'=>$model->id, 'idTree'=>$idTree));
                              
			}				
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
	public function actionUpdate($id, $idTree)
	{
		$modelTree=$this->loadModelTree($idTree);
		$model=$this->loadModel($id, $idTree);
        $model->id_tree = $idTree;
        $model->author = Yii::app()->user->name;
        if ($modelTree->use_tape) $model->general_page=0; 
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['News']))
		{
			$model->attributes=$_POST['News'];
			$model->log_change = LogChange::setLog($model->log_change, 'изменение');
			if($model->save())
                
                // файлы для удаления
                if (isset($_POST['News']['deleteFile']))
                    { $delFile = $_POST['News']['deleteFile']; }
                        else { $delFile = array(); }
                                                           
                // изображения для удаления
                if (isset($_POST['News']['deleteImage']))
                    { $delImage = $_POST['News']['deleteImage']; }
                        else { $delImage = array(); }
                
                // сначала удаляем файлы и изображения помеченные для удаления
                $model->DeleteFilesImages($model->id, $delFile, $delImage, $idTree);
                
                // сохраняем файлы    
                $model->saveFiles($model->id, $idTree);        
                // сохраняем изображения                
                $model->SaveImages($model->id, $idTree);                                
            
			    $this->redirect(array('view','id'=>$model->id, 'idTree'=>$idTree));  
		}

		$this->render('update',array(
			'model'=>$model,
            'modelTree'=>$modelTree,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id, $idTree)
	{
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
                            'where'=>'id_page=:id_page',
                            'params'=>array(':id_page'=>$id),
                        ))->queryAll(),
                    'id','id'),
                    CHtml::listData(Yii::app()->db->createCommand(array(
                            'select'=>'id',
                            'from'=>'{{image}}',
                            'where'=>'id_page=:id_page',
                            'params'=>array(':id_page'=>$id),
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
	   
		/*
        if (!Tree::model()->exists('id=:id AND module=:module', array(':id'=>$idTree,'module'=>'page')))
            throw new CHttpException(404,'Страница не найдена.');
        
        if (!(Yii::app()->user->admin || Access::model()->checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
        */
        
        $modelTree = $this->loadModelTree($idTree);
            
        $model=new NewsSearch('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['News']))
			$model->attributes=$_GET['News'];

		$this->render('admin',array(
			'model'=>$model,
            'modelTree'=>$modelTree,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $idTree)
	{
		
        if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree))
            || !Tree::model()->checkParentRight($idTree))
            throw new CHttpException(403,'Доступ запрещен.');        
        
        $delDate = (Yii::app()->user->admin) ? '' : ' AND date_delete is null';
        
		$model=News::model()->findByPk($id, 'id_tree=:id_tree '.$delDate, 
            array(':id_tree'=>$idTree));                
        
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		return $model;
	}
	
	
	/**
	 * Поиск данных Tree модели
	 * Если данные не найдены возникает HTTP исключение 404
	 * @param int $idTree
	 * @throws CHttpException
	 * @return Tree
	 */
	public function loadModelTree($idTree)
	{
		
		if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree)) || !Tree::model()->checkParentRight($idTree))
			throw new CHttpException(403,'Доступ запрещен.');
				
		$modelTree = Tree::model()->find('id=:id AND '
			. '(id_organization=:organization1 or id_organization=:organization2)',
			array(
				':id'=>$idTree
				,':organization1'=>Yii::app()->session['organization']
				,':organization2'=>'0000'
			));
		if ($modelTree === null)
			throw new CHttpException(404,'Страница не найдена.');
		  
		return $modelTree;
	}
    
        

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='news-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
