<?php

class NewsController extends Controller
{
	
	/**
	 * {@inheritDoc}
	 * @see CController::accessRules()
	 */
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
		VisitNews::saveVisit($id);
		
		$dirImage = str_replace('{code_no}', Yii::app()->session['organization'],
				Yii::app()->params['pathImages']);
		$dirImage = str_replace('{module}', 'news', $dirImage);
		$dirImage = str_replace('{id}', $id, $dirImage);
		
		$dirFile = str_replace('{code_no}', Yii::app()->session['organization'],
				Yii::app()->params['pathDocumets']);
		$dirFile = str_replace('{module}', 'news', $dirFile);
		$dirFile = str_replace('{id}', $id, $dirFile);
				
		$this->render('view',array(
			'model'=>$this->loadModel($id),           
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
	 * Список новостей (материалов)
	 * Условия:
	 *  - если не указан код организации и имя раздела, то вывести новости всех инспекций
	 *  - если указан код организации, но не указан раздел, то вывести список всех новостей организации
	 *  - если указан раздел, но не указан код организации, то вывести список материалов раздела по всем организациям
	 *  - если указан раздел и код организации, то вывести список материалов данной организации
	 * @author tvog17
	 */
	public function actionIndex($organization=null, $section=null)
	{
		
		$organizationModel = null;		
	    // проверка организации
		if ($organization!==null && !$organizationModel = Organization::model()->find('code=:code', [':code'=>$organization]))
		{
			throw new CHttpException(404,'Страница не найдена.');
		}
		
				
		$treeModel = null;
		// проверка раздела
		if ($section!==null && ($treeModel=Tree::model()->find('module=:module and param1=:param1',[':module'=>'news', ':param1'=>$section])) === null)
		{
			throw new CHttpException(404,'Страница не найдена.');
		}
		
		// определение наименование заголовка (breadcrumbs)
		// Главная - Блок - Организация (Все организации)
		if ($treeModel !== null)
		{			
			$breadcrumbs = [
				$treeModel->name => ['news/index', 'section'=>$section],
				(($organizationModel===null) ? 'Все налоговые органы' : $organizationModel->fullName),
			];
		}
		else
		{
			$breadcrumbs = [
				'Новости' => ['news/index'],
				(($organizationModel===null) ? 'Все налоговые органы' : $organizationModel->fullName),
			];
		}
		
		
		$model=new NewsSearch('search');
		$model->unsetAttributes();  // clear any default values
		
		$model->id_organization = $organization;
		$model->param1 = $section;
		
			
		if(isset($_GET['News']))
			$model->attributes=$_GET['News'];


		// левое меню (дополнительное)		
		$menu = [
			['name'=>'Новости', 'link'=>['news/index']],
			['name'=>'Пресс клуб', 'link'=>['news/index', 'section'=>'PressClub']],
			['name'=>'Досуг', 'link'=>['news/index', 'section'=>'Dosug']],
			['name'=>'Обзор СМИ', 'link'=>'#', 'items'=>[
				['name'=>'Фоторепортажи','link'=>['news/index', 'section'=>'SmiPhoto']],
				['name'=>'Видеоматериалы','link'=>['news/index', 'section'=>'SmiVideo']],
				['name'=>'Аудиоматериалы','link'=>['news/index', 'section'=>'SmiAudio']],
				['name'=>'Печать','link'=>['news/index', 'section'=>'SmiPrint']],
			]],	
			['name'=>'Юмор налоговиков', 'link'=>['news/index', 'section'=>'Humor']],
		];
		Menu::$leftMenuAdd = array_merge(Menu::$leftMenuAdd, $menu);
		
		
		$this->render('index',array(
			'model'=>$model->searchPublic(),            
            'organization'=>$organization,
			'allOrganization'=>($organization===null),
			'breadcrumbs' => $breadcrumbs,
		));
		
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
}
