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
        
	    $model = $this->loadModel($id);
	    //$modelTree = $this->loadModelTree($model['id_tree']);
	    
	    $dirImage = str_replace('{code_no}', $model['id_organization'],
				Yii::app()->params['pathImages']);
		$dirImage = str_replace('{module}', $model['module'], $dirImage);
		$dirImage = str_replace('{id}', $id, $dirImage);
		
		$dirFile = str_replace('{code_no}', $model['id_organization'],
				Yii::app()->params['pathDocumets']);
		$dirFile = str_replace('{module}', $model['module'], $dirFile);
		$dirFile = str_replace('{id}', $id, $dirFile);
				
				
		$this->pageTitle = $model['title'];
		
		VisitNews::saveVisit($id);		
		
		if (Yii::app()->request->isAjaxRequest)
    		return $this->renderPartial('_viewAjax',array(
    			'model'=>$model,           
    			'dirImage'=>$dirImage,
    			'dirFile'=>$dirFile,
    		    'files'=>File::filesForDownload($id, 'news'),
    		    'images'=>Image::imagesForDownload($id, 'news'),
    		), false, true);
    		
		return $this->render('view',array(
		    'model'=>$model,
		    'dirImage'=>$dirImage,
		    'dirFile'=>$dirFile,
		    'files'=>File::filesForDownload($id, 'news'),
		    'images'=>Image::imagesForDownload($id, 'news'),
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
	 *  1 если не указан код организации, не имя раздела, то вывести новости всех инспекций
	 *  2 если указан код организации, но не указан раздел, то вывести список всех новостей организации
	 *  3 если указан раздел, но не указан код организации, то вывести список материалов раздела по всем организациям
	 *  4 если указан раздел и код организации, то вывести список материалов данной организации
	 * @author tvog17
	 */
	public function actionIndex($organization=null, $section=null)
	{
		
		$organizationModel = null;		
	    // проверка организации
		if ($organization!==null && !$organizationModel = Yii::app()->db->createCommand()->from('{{organization}}')->where('code=:code', [':code'=>$organization])->query()->read())
		{
			throw new CHttpException(404,'Страница не найдена.');
		}
		
				
		$treeModel = null;
		// проверка раздела
		if ($section!==null && ($treeModel=Yii::app()->db->createCommand()->from('{{tree}}')->where('module=:module and param1=:param1',[':module'=>'news', ':param1'=>$section])->query()->read()) === null)
		{
			throw new CHttpException(404,'Страница не найдена.');
		}
		
		// определение наименование заголовка (breadcrumbs)
		// Главная - Блок - Организация (Все организации)
		if ($treeModel !== null)
		{			
			$breadcrumbs = [
				$treeModel['name'] => ['news/index', 'section'=>$section],
				(($organizationModel===null) ? 'Все налоговые органы' : $organizationModel['code'] . ' (' . $organizationModel['name'] . ')'),
			];
			$this->pageTitle = ($organizationModel === null ? '' : $organizationModel['name'] . ': ') . $treeModel['name'];
		}
		else
		{
			$breadcrumbs = [
				'Новости' => ['news/index'],
			    (($organizationModel===null) ? 'Все налоговые органы' : $organizationModel['code'] . ' (' . $organizationModel['name'] . ')'),
			];
			$this->pageTitle = ($organizationModel === null ? '' : $organizationModel['name'] . ': ') . 'Новости';
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
	 * Новость дня
	 * @return string
	 */
	public function actionNewsDay($id=0)
	{	    
	    $model = NewsSearch::getFeedNewsDay($id);
	    return $this->renderPartial('/site/index/_news', [
	        'model'=> $model,
	        'btnUrl' => [
	            'url'=>$this->createUrl('news/index', array('organization'=>'8600')),
	            'name'=>'Все новости',
	        ],
	    ]);
	}
	
	public function actionNewsIfns()
	{
	    $model = NewsSearch::getFeedIfns();
	    return $this->renderPartial('/site/index/_news', [
	        'model'=> $model,
	        'btnUrl' => [
	            'url'=>$this->createUrl('news/index'),
	            'name'=>'Все новости',
	        ],
	    ]);
	}
	
	public function actionHumor()
	{
	    $model = NewsSearch::feedDopNews('Humor');
	    return $this->renderPartial('/site/index/_news', [
	        'model'=> $model,
	        'btnUrl' => [
	            'url'=>$this->createUrl('news/index', array('section'=>'Humor')),
	            'name'=>'Все материалы',
	        ],
	    ]);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{	   
		//$model = News::model()->findByPk($id, 'date_delete is null and flag_enable=1');
		$model = Yii::app()->db->createCommand()
		  ->select('news.*,tree.module,organization.name as organization_name')
		  ->from('{{news}} news')
		  ->join('{{tree}} tree', 'tree.id=news.id_tree')
		  ->leftJoin('{{organization}} organization', 'organization.code=news.id_organization')
		  ->where('news.id=:id and news.date_delete is null and news.flag_enable=1', [':id'=>$id])
		  ->queryRow();
		       
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		return $model;
	}
	
	
	/**
	 * Retutn model Tree
	 * @param int $id
	 * @throws CHttpException
	 * @return Tree
	 */
	public function loadModelTree($id)
	{	    
	    $model = Tree::model()->findByPk($id, 'date_delete is null');
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
