<?php

/**
 * Управление новостями
 * @author alexeevich
 * @see News
 */
class NewsController extends Controller
{
	
	/**
	 * {@inheritDoc}
	 * @see CController::accessRules()
	 * @return array
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
	 * Вывод новости для просмотра
	 * @param integer $id идентификатор новости	
	 * @see VisitNews
	 */
	public function actionView($id)
	{	   
        $model = $this->loadModel($id);

        // каталог для изображений
	    $dirImage = str_replace('{code_no}', $model['id_organization'],
				Yii::app()->params['pathImages']);
		$dirImage = str_replace('{module}', $model['module'], $dirImage);
		$dirImage = str_replace('{id}', $id, $dirImage);
		
		// каталог для файлов
		$dirFile = str_replace('{code_no}', $model['id_organization'],
				Yii::app()->params['pathDocumets']);
		$dirFile = str_replace('{module}', $model['module'], $dirFile);
		$dirFile = str_replace('{id}', $id, $dirFile);
			
		// заголовок страницы
		$this->pageTitle = $model['title'];
		
		// сохранение информации о визите пользователя
		VisitNews::saveVisit($id);		
		
		// если ajax-запрос, то возвращаем в виде json-формата
		if (Yii::app()->request->isAjaxRequest)
		{
		    echo CJSON::encode([
		        'title'=>$model['title'],
		        'content'=> $this->renderPartial('_viewAjax',array(
        			'model'=>$model,           
        			'dirImage'=>$dirImage,
        			'dirFile'=>$dirFile,
        		    'files'=>File::filesForDownload($id, 'news'),
        		    'images'=>Image::imagesForDownload($id, 'news'),
        		), true, true),
            ]);
		    Yii::app()->end();
		}

		// результат в обычном html-формате
		return $this->render('view',array(
		    'model'=>$model,
		    'dirImage'=>$dirImage,
		    'dirFile'=>$dirFile,
		    'files'=>File::filesForDownload($id, 'news'),
		    'images'=>Image::imagesForDownload($id, 'news'),
		));
	}
    
	/**
	 * Создание новости
	 * В случае успешного сохранения выполняется переадресация на дейтвие 'view'
	 * @see Tree
	 * @see News
	 * @see LogChange
	 * @throws CHttpException
	 */
	public function actionCreate($idTree)
	{        
        $modelTree = Tree::model()->find('id=:id AND module=:module AND organization=:organization', 
            array(':id'=>$idTree,':module'=>'news',':organization'=>Yii::app()->session['code_no']));
       
        if ($modelTree===null)
            throw new CHttpException(404,'Страница не найдена.');
		
        if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
            
        $model=new News;
        $model->id_tree = $idTree;
        $model->flag_enable = true;
        $model->date_start_pub = date('d.m.Y');
        $model->date_end_pub = date('01.m.Y', PHP_INT_MAX);
        $model->author = Yii::app()->user->name;
        $model->general_page=0; 
        		
		if(isset($_POST['News']))
		{
			$model->attributes=$_POST['News'];
            $model->log_change = Log::setLog($model->log_change, 'создание');                                    
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
	 *  1. если не указан код организации, не имя раздела, то вывести новости всех инспекций
	 *  2. если указан код организации, но не указан раздел, то вывести список всех новостей организации
	 *  3. если указан раздел, но не указан код организации, то вывести список материалов раздела по всем организациям
	 *  4. если указан раздел и код организации, то вывести список материалов данной организации
	 * @param string $organization код организации
	 * @param string $section раздел
	 * @see NewsSearch
	 * @throws CHttpException
	 * @author alexeevich
	 * @todo удалить меню, добавить хэш-теги
	 */
	public function actionIndex($organization=null, $section=null)
	{
	    	  
		$organizationModel = null;
	    
	    // проверка организации
		if ($organization!==null 
            && !$organizationModel = Yii::app()->db->createCommand()->from('{{organization}}')->where('code=:code', [':code'=>$organization])->query()->read())
		{
			throw new CHttpException(404,'Страница не найдена.');
		}
				
		$treeModel = null;
		
		// проверка раздела
		if ($section!==null 
		    && ($treeModel=Yii::app()->db->createCommand()->from('{{tree}}')
		      ->where('module=:module and param1=:param1',[':module'=>'news', ':param1'=>$section])->query()->read()) === null)
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
			'model'=>$model->searchPublicOLD(),
		    'linkActionNews' => ($treeModel['id'] !==null ? $this->createUrl('news/newsTree', ['idTree'=>$treeModel['id']]) : null),
            'organization'=>$organization,
			'allOrganization'=>($organization===null),
			'breadcrumbs' => $breadcrumbs,
		    'organizationModel' => $organizationModel,
		));
		
	}
	
	/**
	 * Новости УФНС (новость дня)
	 * Если $id == 0, то выводятся последние 5 материалов
	 * Если $id <> 0, то выводятся последние материалы, идентификатор у которых < $id 
	 * @param int $id идентификатор матерала
	 * @see NewsSearch
	 * @return string
	 * @deprecated
	 */
	public function actionNewsDay($id=0)
	{	    	    
	    $model = NewsSearch::getFeedNewsDay($id);
	    $lastId = isset($model[count($model)-1]['id']) ? $model[count($model)-1]['id'] : 0; 
	    return $this->renderPartial('/site/index/_news', [
	        'urlAjax'=>Yii::app()->controller->createUrl('news/newsDay', ['id'=>$lastId]),
	        'type'=>'news_day',
	        'model'=> $model,
	        'lastId'=>$lastId,
	        'btnUrl' => [
	            'url'=>$this->createUrl('news/index', array('organization'=>'8600')),
	            'name'=>'Все новости',
	        ],
	    ]);
	}
	
	/**
	 * Новости все
	 * Если $id == 0, то выводятся последние 5 материалов
	 * Если $id <> 0, то выводятся последние материалы, идентификатор у которых < $id
	 * @param int $id идентификатор матерала
	 * @param string $organization код организации
	 * @see NewsSearch
	 * @return string
	 */
	public function actionNews($id=0, $organization=null)
	{	    	    
	    $model=new NewsSearch('searchPublic');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['News']))
	       $model->attributes=$_GET['News'];
	    
	    if ($organization!=null)
	        $model->id_organization = $organization;
	    
        $model = $model->searchPublic($id);
        
        $lastId = isset($model[count($model)-1]['id']) ? date('YmdHis', strtotime($model[count($model)-1]['date_create'])) . $model[count($model)-1]['id'] : 0;
	        
        $this->renderPartial('/news/feed',array(
            'model'=>$model,
            'lastId'=>$lastId,
            'type'=>'news',
            'urlAjax'=>Yii::app()->controller->createUrl('news/news', ['id'=>$lastId]),
        ));
	}
	
	/**
	 * Новости из раздела (структуры)
	 * @param int $idTree идентификатор раздела
	 * @param int $id идентификатор новости
	 * @see NewsSearch
	 * @return string
	 */
	public function actionNewsTree($idTree, $id=0)
	{
	    $model=new NewsSearch('searchPublic');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['News']))
	       $model->attributes=$_GET['News'];
	    	   
	    if ($idTree!=null)
	        $model->id_tree = $idTree;
	    
        $model = $model->searchPublic($id, false);
        
        $lastId = isset($model[count($model)-1]['id']) ? date('YmdHis', strtotime($model[count($model)-1]['date_create'])) . $model[count($model)-1]['id'] : 0;
	        
        $this->renderPartial('/news/feed',array(
            'model'=>$model,
            'lastId'=>$lastId,
            'type'=>'news',
            'urlAjax'=>Yii::app()->controller->createUrl('news/newsTree', ['id'=>$lastId, 'idTree'=>$idTree]),
        ));
	}
	
	/**
	 * Новости ИФНС
	 * Если $id == 0, то выводятся последние 5 материалов
	 * Если $id <> 0, то выводятся последние материалы, идентификатор у которых < $id 
	 * @param int $id идентификатор материала
	 * @see NewsSearch
	 * @return string
	 * @deprecated
	 */
	public function actionNewsIfns($id=0)
	{
	    $model = NewsSearch::getFeedIfns($id);
	    $lastId = isset($model[count($model)-1]['id']) ? $model[count($model)-1]['id'] : 0; 
	    return $this->renderPartial('/site/index/_news', [
	        'urlAjax'=>Yii::app()->controller->createUrl('news/newsIfns', ['id'=>$lastId]),
	        'type'=>'news_ifns',
	        'model'=> $model,
	        'lastId'=>$lastId,
	        'btnUrl' => [
	            'url'=>$this->createUrl('news/index'),
	            'name'=>'Все новости',
	        ],
	    ]);
	}
	
	/**
	 * Раздел "Юмор налоговиков"
	 * Если $id == 0, то выводятся последние 5 материалов
	 * Если $id <> 0, то выводятся последние материалы, идентификатор у которых < $id 
	 * @param int $id идентификатор материала
	 * @see NewsSearch
	 * @return string
	 * @deprecated
	 */
	public function actionHumor($id=0)
	{
	    $model = NewsSearch::feedDopNews('Humor', $id);
	    $lastId = isset($model[count($model)-1]['id']) ? $model[count($model)-1]['id'] : 0; 
	    return $this->renderPartial('/site/index/_news', [
	        'urlAjax'=>Yii::app()->controller->createUrl('news/Humor', ['id'=>$lastId]),
	        'type'=>'humor',
	        'model'=> $model,
	        'lastId'=>$lastId,
	        'btnUrl' => [
	            'url'=>$this->createUrl('news/index', array('section'=>'Humor')),
	            'name'=>'Все материалы',
	        ],
	    ]);
	}

	public function actionTagNews($id=0, $q)
	{
		if (strlen(trim($q)) < 2)
		{
			throw new CHttpException(400,'Некорректный запрос!');
		}

		$model=new NewsSearch('searchPublic');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['News']))
	       $model->attributes=$_GET['News'];
		
		$model->tags = $q;
        $model = $model->searchPublic($id);
        
        $lastId = isset($model[count($model)-1]['id']) ? date('YmdHis', strtotime($model[count($model)-1]['date_create'])) . $model[count($model)-1]['id'] : 0;
	        
        $this->renderPartial('/news/feed',array(
            'model'=>$model,
            'lastId'=>$lastId,
            'type'=>'news',
            'urlAjax'=>Yii::app()->controller->createUrl('news/tagNews', ['q'=>$q, 'id'=>$lastId]) //Yii::app()->controller->createUrl('news/newsTree', ['id'=>$lastId, 'idTree'=>$idTree]),
		));				
	}

	public function actionTag($q)
	{
		if (strlen(trim($q)) < 2)
		{
			throw new CHttpException(400,'Некорректный запрос!');
		}

		$this->render('/news/tag',array(
			'q'=>$q,
			'breadcrumbs'=>[$q],
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 * @uses actionView()
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
	 * @deprecated
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
