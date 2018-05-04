<?php

/**
 * @deprecated
 * @author 8600-90331
 */
class Structure extends Controller
{
    
    
    protected $alias;    
	
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
	 * Новости (структура)
	 * @param $idTree integer (идентификатор Tree)
	 * @desc
	 * 1. Поиск записи Tree.id=$idTree, если запись не найдена, то return http 404
	 * 2. Произвести поиск новостей с данным idTree
	 * 2.1. Если новость только 1, то показать ее сразу в представлении view
	 * 2.2. Если более 1 новости, то вывести их как миниатюры
	 * 2.3. Если новостей не найдено, то вывести структуру дерева Tree (дочерние объекты)
	 * @author oleg
	 * @version 18.10.2017
	 */	
	protected function indexByIdTree($idTree)
	{
	    if ($idTree==null || !is_numeric($idTree))
	        throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	    
	    // 1. Поиск записи в Tree
	    $modelTree = Yii::app()->db->createCommand()
	       ->from('{{tree}}')
	       ->where('id=:id', [':id'=>$idTree])
	       ->queryRow();
	    if ($modelTree==null)
	        throw new CHttpException(404,'The requested page does not exist.');
	    
	    // 2. Поиск новостей с id_tree = $idTree
        $modelNews = News::model()->findAll('id_tree=:id_tree and date_delete is null and date_start_pub <= getdate() and date_end_pub >= getdate() and flag_enable = 1',
            [':id_tree'=>$idTree]);
      
	   // 2.1. Если только 1 новость
	   if (count($modelNews)==1)
	   {
	       $dirs = FileHelper::fileImageDirByNewsId($modelNews[0]->id, $modelNews[0]->id_organization);
	       return $this->render('view', [
	           'modelTree'=>$modelTree,
	           'modelNews'=>$modelNews,
	           'dirImage'=>$dirs['dirImage'],
	           'dirFile'=>$dirs['dirFile'],
	       ]);
	   }	   
	   // 2.2. Если более 1 новости
	   elseif (count($modelNews)>1)
	   {
	       $modelNewsSearch = new NewsSearch('search');
	       $modelNewsSearch->unsetAttributes();
	       $modelNewsSearch->id_tree = $idTree;
	       
	       return $this->render('index', [
	           'modelTree'=>$modelTree,
	           'modelNews'=>$modelNewsSearch,	           
	       ]);
	   }	   
	   // 2.3. Если вообще нет новостей
	   else 
	   {
	       $structure = $this->structureTree($idTree);
	       return $this->render('tree', [
	           'modelTree'=>$modelTree,
	           'structure'=>$structure,
	       ]);
	   }	    
	}
	
	
	
	private function structureTree($idTree)
	{
	    $resultString = '';
	    
	    $modelTree = Yii::app()->db->createCommand()
    	    ->from('{{tree}}')
    	    ->where('id_parent=:id_parent', [':id_parent'=>$idTree])
    	    ->queryAll();
	    
	    if (count($modelTree)==0)
	        return  '';
	    
        $resultString .= "<ul class=\"\">\n";
        	        
        if ($modelTree !== null)
        {	            
            foreach ($modelTree as $tree)
            {
                $resultString .= "<li>" . CHtml::link($tree['name'], [$this->alias . '/index', 'id'=>$tree['id']]) . "</li>\n";
                $resultString .= $this->structureTree($tree['id']);
            }
        }
        $resultString .= "</ul>\n";
        
        return $resultString;
	}
	
	
	
	public function getMenu($idTree)
	{
	    $resultMenu = array();
	
	    $model = Yii::app()->db->createCommand()
    	    ->from('{{tree}}')
    	    ->where('id_parent=:id_parent', [':id_parent'=>$idTree])
    	    ->select('id, name')
    	    ->queryAll();
	    
	    foreach ($model as $m)
	    {
	        $resultMenu[] = array(
	            'name'=>$m['name'],
	            'link'=>[$this->alias . '/index', 'id'=>$m['id']],
	            'items'=>$this->getMenu($m['id']),
	        );
	    }
	    return $resultMenu;
	}
	
	
	
	
	
	
	public function actionView($id)
	{	    
	    // 1. model Department
	    $this->model = $this->loadModel($id);
	    // load left menu
	    $this->loadMenu($this->model);
	    
	    if ($idTree==null)
	    {
            return $this->showDepartment($id);   
	    }
	    else
	    {
	        return $this->showTreeNode($idTree);
	    }
	}
	
	
	
	/**
	 * 1. Если присутсвует (и существует) $idTree и в текущем классе имеется
	 * функция с именем "render_$module$", где $module$ - имя модуля (Tree->module)
	 * то вызываем эту функцию и передаем ей управление (изначально было задумано так
	 * для рейтингов, но может пригодится где-то еще)
	 * 
	 * 2. Если присутсвует (и существует) $idTree, но не проходит условия п.1, то: 
	 * 2.1. Проверяем если есть только 1 новость, то выводим ее, причем сразу в представлении view
	 * 2.2. Если новостей больше, то вывести просто их список (представление index)
	 * 
	 */
	private function showTreeNode($idTree)
	{
	    // 1 подгрузка по модулю (например, райтинги)
	    $this->modelTree = $this->loadModelTree($idTree);
	    $methodName = 'render_' . $this->modelTree->module;
	    if (in_array($this->modelTree->module, $this->useModules) && method_exists($this, $methodName))
	    {
	        return call_user_func(array($this, $methodName), $this->model->id, $idTree);
	    }
	    
	    
	    // 2 Поиск новостей по текущему разделу
	    $tree = ($idTree===null) ? $this->model->id_tree : $idTree;
	    $modelNews = News::model()->findAll('id_tree=:id_tree and date_delete is null and flag_enable=1 and date_start_pub < getdate() and date_end_pub > getdate()', [':id_tree'=>$tree]);
	    
	    $modelNewsSearch = new NewsSearch('search');
	    $modelNewsSearch->unsetAttributes();
	    /*if (isset($_GET['News']))
	       $modelNewsSearch->attributes=$_GET['News'];*/
        $modelNewsSearch->id_tree = $tree;
	       
        // 2.1 Проверить если в новостях текущего раздела имеется 1 новость,
        // то вывести эту новость в 
	    if (count($modelNews) == 1 && isset($modelNews[0]) && $modelNews[0] instanceof News)
        {
            $dirs = FileHelper::fileImageDirByNewsId($modelNews[0]->id, $modelNews[0]->id_organization);
            return $this->render('view', [
                'model'=>$this->model,
                'modelNews'=>$modelNews,
                'dirImage'=>$dirs['dirImage'],
                'dirFile'=>$dirs['dirFile'],
            ]);            
        }
        // 2.2 Вывести весь список новостей
        elseif (count($modelNews)>1)
        {            
            return $this->render('news', [
                'model'=>$this->model,
                'modelNews'=>$modelNewsSearch,
                'modelTree'=>$this->modelTree,
            ]);            
        }	
	        
	    return $this->render('noData', ['model'=>$this->model]); // нет данных
	}
	
	
	/**
	 * Главная страница отдела
	 * 	 
	 * 1. Поиск указанного отдела (с id = $id) для просмотра настроек отдела
	 * 1.1. Если не найдно, то возвращается ошибка http 404
	 * 1.2. Если у отдела свойство general_page = 0 (отображать первую новость),
	 * то необходимо выполнить поиск первой новости в корне отдела (отсортировав id asc)
	 * Если не удалось найти новость, то устанавливается флаг того, 
	 * что ничего не найдено ($flagFind = false)
	 * 1.3. Если у отдела свойство general_page = 1  (показывать новость из списка), то
	 * нужно найти новость в модели News с id_tree = Department->general_page_tree_id
	 * (+ условия не удалена и не заблокирована), если нашлась то показать ее, иначе 
	 * установить флаг того, что ничего не найдено ($flagFind = false)
	 * 1.4. Если у отдела свойство general_page = 2 (показывать структуру отдела), то 
	 * проверить включена ли опция Department->use_card и если да, то вывести структуру,
	 * иначе установить флаг того, что ничего не найдено ($flagFind = false)
	 * 2. Если ни одно из вышеприведенных действий не выполнено, 
	 * то показать дерево отдела (список подразделов)
	 * 	 
	 * @param int $id
	 * @author oleg
	 * 
	 */
	private function showDepartment($id)
	{
	     	  
	    
	    // 1.2 show first news
	    if ($this->model->general_page_type == Department::GP_SHOW_FIRST_NEWS)
	    {
            /*
            $modelFirstNews = Yii::app()->db->createCommand()
               ->from('{{news}}')
               ->where('id_tree=:id_tree and date_delete is null and date_start_pub <= getdate() and date_end_pub >= getdate() and flag_enable = 1', [':id_tree'=>$this->model->id_tree])
               ->query()->read();*/
	        $modelFirstNews = News::model()->findAll('id_tree=:id_tree and date_delete is null and date_start_pub <= getdate() and date_end_pub >= getdate() and flag_enable = 1', 
	            [':id_tree'=>$this->model->id_tree]);
	       
            if (count($modelFirstNews) > 0)
            {
	           // render news
	           $dirs = FileHelper::fileImageDirByNewsId($modelFirstNews[0]->id, $modelFirstNews[0]->id_organization);
	           return $this->render('view', [
	               'model'=>$this->model,
	               'modelNews'=>$modelFirstNews,
	               'dirImage'=>$dirs['dirImage'],
	               'dirFile'=>$dirs['dirFile'],
	           ]);
            }
	    }
	    
	    // 1.3 show news from list 
	    if ($this->model->general_page_type == Department::GP_SHOW_NEWS_FROM_LIST && is_numeric($this->model->general_page_tree_id))
	    {
	        $modelFromListNews = Yii::app()->db->createCommand()
	           ->from('{{news}}')
	           ->where('id_tree=:id_tree and id=:id and date_delete is null 
                    and date_start_pub <= getdate() and date_end_pub >= getdate() and flag_enable = 1', [
                        ':id_tree'=>$this->model->id_tree,
                        ':id'=>$this->model->general_page_tree_id,
                    ])
	           ->query()->read();
           if ($modelFromListNews !== null)
           {
               // render news
               $dirs = FileHelper::fileImageDirByNewsId($modelFromListNews['id'], $modelFromListNews['id_organization']);
               return $this->render('view', [
                   'model'=>$this->model,
                   'modelNews'=>$modelFromListNews,
                   'dirImage'=>$dirs['dirImage'],
                   'dirFile'=>$dirs['dirFile'],
               ]);
           }
	    }
	    
	    // 1.4. show struct department
	    if ($this->model->general_page_type == Department::GP_SHOW_STRUCT && $this->model->use_card)
	    {
	        return $this->render('struct', [
	            'model'=>$this->model,
	            'arrayCard'=>$this->structCards($id),
	        ]);	        
	    }
	    
	    
	    // 2. Показать дерево отдела (если есть)
	    $treeDepartment = $this->departmentTree($this->model->id_tree, $id);
	    if ($treeDepartment != null)
	    {
	        return $this->render('tree', [
	            'model'=>$this->model,
	            'treeDepartment'=>$treeDepartment,
	        ]);
	    }
	    
	    return $this->render('noData', ['model'=>$this->model]); // нет данных
	}
	
	
	/**
	 * Подразделы отдела
	 * @param int $idTree
	 * @param int $idDepartment
	 * @return string
	 * @author oleg
	 * @version 12.10.2017
	 */
	private function departmentTree($idTree, $idDepartment, $flagStruct=false)
	{
	    $resultString = '';
	    
	    $modelTree = Yii::app()->db->createCommand()
	       ->from('{{tree}}')
	       ->where('id_parent=:id_parent', [':id_parent'=>$idTree])
	       ->queryAll();
	    
	    
	    if (count($modelTree)==0 && !$flagStruct && !$this->model->use_card)
	        return  '';
	    
        $resultString .= "<ul class=\"\">\n";
        
        if ($this->model->use_card && !$flagStruct)
        {
            $resultString .= "<li>" . CHtml::link('Структура', ['department/struct', 'id'=>$idDepartment]) . "</li>\n";
            $flagStruct=true;
        }
	    
	    if ($modelTree !== null)
	    {
	        
	        foreach ($modelTree as $tree)
	        {
	            $resultString .= "<li>" . CHtml::link($tree['name'], ['department/view', 'id'=>$idDepartment, 'idTree'=>$tree['id']]) . "</li>\n";
	            $resultString .= $this->departmentTree($tree['id'], $idDepartment, $flagStruct);
	        }
	        
	    }	
	    $resultString .= "</ul>\n";
	    
	    return $resultString;	        
	}
	
	
	
	
	/**
	 * 
	 * @param int $id - RatingMain->id
	 */
	public function actionRatingData($id)
	{
		$model = $this->loadModelRatingMain($id);
		$modelTree = $this->loadModelTree($model->id_tree);
				
		$this->renderPartial('ratingMain', [
			'model'=>$model,
			'modelYear'=>$this->loadModelRatingDataYears($model->id),
		    'modelTree'=>$modelTree,
		]);
	}
	
	
	/**
	 * Структура отдела с ИД = $id
	 * @param int $id
	 * @author oleg
	 * @version 06.03.2017 - create
	 */
	public function actionStruct($id)
	{
		$model = $this->loadModel($id);
		$this->loadMenu($model);
		$this->render('struct', ['model'=>$model, 'arrayCard'=>$this->structCards($id)]);
	}
	
	
	
	
	public function actionViewPage($id)
	{
		$model = $this->loadModel($id);
		$this->loadMenu($model);
		$this->render('news', [
			'model'=>$model,
			'modelNews'=>News::model()->findAll('id_tree=:id_tree', [':id_tree'=>$model->id_tree]),				
		]);
	}
		
	
	
	/**
	 * Получение списка карточек сотрудников отдела
	 * адаптированного для вывода в представление
	 * @param int $id
	 * @return array
	 * @author oleg
	 * @version 06.03.2017 - create
	 */
	private function structCards($id)
	{
		$arrayCard = array();
		$modelCard = DepartmentCard::model()->findAll('id_department=:id', [':id'=>$id]);
		/*$modelCard = Yii::app()->db->createCommand()
		  ->from('{{department_card}}')
		  ->where('id_department=:id_department', [':id_department'=>$id])
		  ->queryAll();*/
		
		foreach ($modelCard as $card)
		{
			$arrayCard[$card['user_level']][] = [
				'user_photo' => $card['user_photo_check'],
				'user_fio' => $card['user_fio'],
				'user_rank' => $card['user_rank'],
				'user_position' => $card['user_position'],
				'user_telephone' => $card['user_telephone'],
				'user_resp' => $card['user_resp'],
			];
		}			
		return $arrayCard;
	}
	
	
	/**
	 * Меню отдела
	 * @param Department $model
	 * @author oleg
	 * @version 06.03.2017 - create
	 */
	private function loadMenu($model)
	{
		if ($model->use_card)
		{
			Menu::$leftMenuAdd = array_merge(Menu::$leftMenuAdd,
				[['name'=>'Структура', 'link'=>['department/struct', 'id'=>$model->id]]]
			);
		}
		
		if ($menu = $model->menu)
		{			
			Menu::$leftMenuAdd = array_merge(Menu::$leftMenuAdd, $menu);
		}		
	}
	
	
	/**
	 * Render for module ratings
	 * @param int $idDepartment
	 */
	private function render_ratingData($idDepartment, $idTree)
	{
		$model = $this->loadModelRatingMainByTreeId($idTree);
		$this->render('rating', [
			'model'=>$model, 			
			'modelDepartment'=>$this->loadModel($idDepartment),
			'modelTree'=>$this->modelTree,			
		]);
	}
	
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{	   
		$model = Department::model()->findByPk($id);		
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		return $model;
	}
	
	// load model Tree
	public function loadModelTree($id)
	{
		$model = Tree::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		return $model;
	}
	
	// load model RatingMain
	public function loadModelRatingMain($pk)
	{
		$model = RatingMain::model()->findByPk($pk);
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		return $model;
	}
		
	
	// load models RatingMain
	public function loadModelRatingMainByTreeId($idTree)
	{
		$model = RatingMain::model()->findAll('id_tree=:id_tree', [':id_tree'=>$idTree]);
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		return $model;
	}
	
	// load model RatingData
	// @todo add order!! from department settings 
	public function loadModelRatingDataYears($idMain)
	{		
		$model = Yii::app()->db->createCommand();
		$model->from('{{rating_data}}');
		$model->select('rating_year');			
		$model->where('id_rating_main=:id', [':id'=>$idMain]);
		$model->distinct = true;
		return CHtml::listData($model->queryAll(), 'rating_year', 'rating_year');
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
