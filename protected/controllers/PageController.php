<?php

class PageController extends Controller
{
	
    public function accessRules()
	{
		return array(
			 array('allow',                
                'users'=>array('@'),
            ),            
		);
	}
    
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		
		$dirImage = str_replace('{code_no}', Yii::app()->session['organization'],
				Yii::app()->params['pathImages']);
		$dirImage = str_replace('{module}', 'news', $dirImage);
		$dirImage = str_replace('{id}', $id, $dirImage);
		
		$dirFile = str_replace('{code_no}', Yii::app()->session['organization'],
				Yii::app()->params['pathDocumets']);
		$dirFile = str_replace('{module}', 'news', $dirFile);
		$dirFile = str_replace('{id}', $id, $dirFile);
		
		
		$model=$this->loadModel($id);
		$modelTree=Tree::model()->findByPk($model->id_tree);
		
		
		$this->render('view',array(
			'model'=>$model,
			'modelTree'=>$modelTree,
			'dirImage'=>$dirImage,
			'dirFile'=>$dirFile,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($idTree)
	{        
        
        $modelTree = Tree::model()->find('id=:id AND module=:module AND organization=:organization', 
            array(':id'=>$idTree,':module'=>'news',':organization'=>Yii::app()->session['code_no']));
       
        if ($modelTree===null)
            throw new CHttpException(404,'Страница не найдена.');
		
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
			$model->attributes=$_POST['News'];
            $model->log_change = LogChange::setLog($model->log_change, 'создание');
                                    
			if($model->save()) {
			    
                // сохраняем файлы                
                $model->saveFiles($model->id, $idTree);
                // сохраняем изображения
                $model->saveImages($model->id, $idTree);
                // сохраняем миниатюра изображения
                $model->saveThumbailForNews($model);
                
                $this->redirect(array('view','id'=>$model->id, 'idTree'=>$idTree));  
                              
			}				
		}

		$this->render('create',array(
			'model'=>$model,
            'idTree'=>$idTree,
		));        
	}

	

	/**
	 * Manages all models.
	 */
	public function actionIndex($organization=null, $page=null)
	{
	    
		if (empty($page))
			$this->redirect('default');
		
		$modelTree = Tree::model()->find('param1=:param1', [':param1'=>$page]);
		if ($modelTree == null)
			$this->redirect('default');
		
			
		// проверка организации
		/*if ($organization!==null && !Organization::model()->exists('code=:code', [':code'=>$organization]))
			$organization = null;*/
		if (!empty($organization))
		{
			$organization = Organization::model()->findByPk($organization);			
		}
		
		$model=new News('search');
		$model->unsetAttributes();  // clear any default values
		if ($organization !== null)
			$model->id_organization = $organization;
		
		if(isset($_GET['News']))
			$model->attributes=$_GET['News'];
			
		$this->render('index',array(
			'model'=>$model, 
			'modelTree'=>$modelTree,
            //'organization'=>$organization,
			//'allOrganization'=>($organization===null),
			'page'=>$page,
			'organization'=>$organization,
		));
		
	}
	
	
	public function actionDefault()
	{
		$model = Tree::model()->findAll('module=:module', [':module'=>'page']);
		$this->render('default', ['model'=>$model]);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{	   
		$model = News::model()->findByPk($id, 'date_delete is null and flag_enable=1'); 
		       
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		return $model;
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
	
	
	/*
	protected function listPage()
	{
		return [
			'PressClub' => 'Пресс клуб',
			'Dosug' => 'Досуг',
			'SmiPhoto' => 'Обзор СМИ: Фоторепортажи',
			'SmiVideo' => 'Обзор СМИ: Видеоматериалы',
			'SmiAudio' => 'Обзор СМИ: Аудиоматериалы',
			'SmiPrint' => 'Обзор СМИ: Печать',
			'SmiMaterial' => 'Обзор СМИ: Материалы Управления',
		];
	}*/
	
	
	
}
