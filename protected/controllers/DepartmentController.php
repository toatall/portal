<?php

/**
 * Управление отделами
 * @author alexeevich
 * @see Department
 */
class DepartmentController extends Controller
{
	
	/**
	 * Используемые модули
	 * @var array
	 */
	private $useModules = array(
		'ratingData', // рейтинги
	);
	
	/**	
	 * Модель отдела
	 * @var Department
	 */
	private $model = null;
	
	/**
	 * Модель структуры
	 * @var Tree
	 */
	private $modelTree = null;
	
	
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
	 * Возвращается модель Department
	 * Поиск выполняется в БД по идентификатору $id
	 * В случае, если запись не найдена, то возвращается исключение CHttpException
	 * @param integer the ID of the model to be loaded
	 * @see Department
	 * @throws CHttpException
	 * @return Department
	 * @uses actionView()
	 * @uses actionStruct()
	 * @uses render_ratingData()
	 */
	public function loadModel($id)
	{
	    $model = Department::model()->findByPk($id);
	    if($model===null)
	        throw new CHttpException(404,'Страница не найдена.');
        return $model;
	}
	
	/**
	 * Возвращается модель Tree
	 * Поиск выполняется в БД по идентификатору $id
	 * В случае, если запись не найдена, то возвращается исключение CHttpException
	 * @param int $id идентификатор
	 * @see Tree
	 * @throws CHttpException
	 * @return Tree
	 * @uses showTreeNode()
	 * @uses actionRatingData()
	 */
	public function loadModelTree($id)
	{
	    $model = Tree::model()->findByPk($id);
	    if($model===null)
	        throw new CHttpException(404,'Страница не найдена.');
        return $model;
	}
	
	/**
	 * Возвращается модель RatingMain
	 * Поиск выполняется в БД по идентификатору $pk
	 * В случае, если запись не найдена, то возвращается исключение CHttpException
	 * @param int $pk идентификатор
	 * @see RatingMain
	 * @exception CHttpException
	 * @return RatingMain
	 * @uses actionRatingData()
	 */
	public function loadModelRatingMain($pk)
	{
	    $model = RatingMain::model()->findByPk($pk);
	    if($model===null)
	        throw new CHttpException(404,'Страница не найдена.');
        return $model;
	}
	
	/**
	 * Возвращается модель RatingMain
	 * Поиск выполняется в БД по идентификатору родителя $idTree
	 * В случае, если запись не найдена, то возвращается исключение CHttpException
	 * @param int $idTree идентификатор родителя
	 * @see RatingMain
	 * @throws CHttpException
	 * @return RatingMain[]
	 * @uses render_ratingData()
	 */
	public function loadModelRatingMainByTreeId($idTree)
	{
	    $model = RatingMain::model()->findAll('id_tree=:id_tree', [':id_tree'=>$idTree]);
	    if($model===null)
	        throw new CHttpException(404,'Страница не найдена.');
        return $model;
	}
	
	/**
	 * Возвращается список годов, 
	 * на основании размещенных рейтингов 
	 * @param int $idMain идентификатор рейтинга
	 * @see CHtml
	 * @return array
	 * @uses actionRatingData()
	 */
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
	 * @deprecated
	 */
	protected function performAjaxValidation($model)
	{
	    throw new CHttpException(410);
	    if(isset($_POST['ajax']) && $_POST['ajax']==='news-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	}
	
	/**
	 * Список отделов	 
	 * @see Department
	 */
	public function actionIndex()
	{
	    $model = Department::model()->findAll();
	    $this->render('index', [
	        'model'=>$model,
	    ]);
	}
	
	/**
	 * Отображение материала отдела
	 * 1. Если $idTree != null и в текущем классе имеется
	 * функция с именем "render_$module$", где $module$ - имя модуля (Tree->module)
	 * то вызываем эту функцию и передаем ей управление (изначально было задумано так
	 * для рейтингов, но может пригодится где-то еще)
	 * 
	 * 2. Если $idTree != null, то смотрим в настройки отдела.
	 * 2.1. Если Department->general_page_type == 0 (отображать первую новость), то
	 * нужно попытаться найти первую новость и показать ее (отсортировав по id),
	 * если не удалось найти то установить флаг того, что ничего не найдено
	 * 2.2. Если Department->general_page_type == 1 (показывать новость из списка), то
	 * нужно найти новость в модели News с id_tree = Department->general_page_tree_id
	 * (+ условия не удалена и не заблокирована), если нашлась то показать ее, иначе 
	 * установить флаг того, что ничего не найдено
	 * 2.3. Если Department->general_page_type == 2 (показывать структуру отдела), то 
	 * проверить включена ли опция Department->use_card и если да, то вывести структуру,
	 * иначе установить флаг того, что ничего не найдено
	 * 2.4. Если имеется флаг, что ничего не найдено, то проверить есть ли дочерние объекты
	 * в модели Tree и если есть, то вывести эту структуру, иначе вывести, что нет данных
	 * 
	 * 3. Если присутсвует (и существует) $idTree, но не проходит условия п.1, то: 
	 * 3.1. Проверяем если есть только 1 новость, то выводим ее, причем сразу в представлении view
	 * 3.2. Если новостей больше, то вывести просто их список (представление index)
	 * 
	 * Где Department - это модель текущего отдела (напимер, так: $this->model = $this->loadModel($id))
	 * 
	 * @author oleg
	 * @uses
	 */	
	public function actionView($id, $idTree=null)
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
	 * Информация по отделу (список новостей, или сама новость)
	 * 1. Если $idTree != null и в текущем классе имеется
	 * функция с именем "render_$module$", где $module$ - имя модуля (Tree->module)
	 * то вызываем эту функцию и передаем ей управление (изначально было задумано так
	 * для рейтингов, но может пригодится где-то еще)
	 * 
	 * 2. Если присутсвует (и существует) $idTree, но не проходит условия п.1, то: 
	 * 2.1. Проверяем если есть только 1 новость, то выводим ее, причем сразу в представлении view
	 * 2.2. Если новостей больше, то вывести просто их список (представление index)
	 * @author oleg
	 * @see News
	 * @see NewsSearch
	 * @see FileHelper
	 * @see File
	 * @see Image
	 * @uses actionView()
	 */
	private function showTreeNode($idTree)
	{
	    // 1. подгрузка по модулю (например, райтинги)
	    $this->modelTree = $this->loadModelTree($idTree);
	    $methodName = 'render_' . $this->modelTree->module;
	    if (in_array($this->modelTree->module, $this->useModules) && method_exists($this, $methodName))
	    {
	        return call_user_func(array($this, $methodName), $this->model->id, $idTree);
	    }
	    
	    // 1/2. Если не указан модуль, то показать подразделы
	    if ($this->modelTree->module == null)
	    {
    	    $treeDepartment = $this->departmentTree($idTree, $this->model->id, true);
    	    if ($treeDepartment != null)
    	    {
    	        return $this->render('tree', [
    	            'model'=>$this->model,
    	            'treeDepartment'=>$treeDepartment,
    	            'breadcrumbsTreePath'=>$this->breadcrumbsTreePath($idTree),
    	        ]);
    	    }
	    }
	    
	    // 2. Поиск новостей по текущему разделу
	    $tree = ($idTree===null) ? $this->model->id_tree : $idTree;
	    $modelNews = News::model()->findAll('id_tree=:id_tree and date_delete is null and flag_enable=1 and date_start_pub < getdate() and date_end_pub > getdate()', [':id_tree'=>$tree]);
	    
	    $modelNewsSearch = new NewsSearch('searchPublic');
	    $modelNewsSearch->unsetAttributes();	    
        $modelNewsSearch->id_tree = $tree;
	       
        // 2.1. Проверить если в новостях текущего раздела имеется 1 новость,
        // то вывести эту новость в 
	    if (count($modelNews) == 1 && isset($modelNews[0]) && $modelNews[0] instanceof News)
        {
            $dirs = FileHelper::fileImageDirByNewsId($modelNews[0]->id, $modelNews[0]->id_organization);
            return $this->render('view', [
                'model'=>$this->model,
                'modelNews'=>$modelNews,
                'dirImage'=>$dirs['dirImage'],
                'dirFile'=>$dirs['dirFile'],
                'files'=>File::filesForDownload($modelNews[0]['id'], 'news'),
                'images'=>Image::imagesForDownload($modelNews[0]['id'], 'news'),
                'breadcrumbsTreePath'=>$this->breadcrumbsTreePath($idTree),
            ]);            
        }
        // 2.2. Вывести весь список новостей
        elseif (count($modelNews)>1)
        {            
            $modelNewsSearch = $modelNewsSearch->searchPublic(0, false);
            
            $lastId = isset($modelNewsSearch[count($modelNewsSearch)-1]['id']) 
                    ? date('YmdHis', strtotime($modelNewsSearch[count($modelNewsSearch)-1]['date_create'])) . $modelNewsSearch[count($modelNewsSearch)-1]['id'] 
                    : 0;
            
            return $this->render('news', [
                'model'=>$this->model,
                'modelNews'=>$modelNewsSearch,
                'modelTree'=>$this->modelTree,
                'type'=>'department',
                'urlAjax'=>Yii::app()->controller->createUrl('news/newsTree', ['id'=>$lastId, 'idTree'=>$this->modelTree->id]),
                'lastId'=>$lastId,
            ]);            
        }	
        
	    return $this->render('noData', ['model'=>$this->model]); // представление с информацией, что нет данных
	}
	
	private function breadcrumbsTreePath($idTree)
	{
	    if ($this->model->id_tree == $idTree)
	        return [];
	    
	    $modelTree = Yii::app()->db->createCommand()
	       ->from('{{tree}}')
	       ->where('id=:id and date_delete is null', [':id'=>$idTree])
	       ->queryRow();
	    
	    if ($modelTree==null)
	        return [];
	    
	    return array_merge($this->breadcrumbsTreePath($modelTree['id_parent']),
	        [$modelTree['name'] => ['department/view', 'id'=>$this->model->id, 'idTree'=>$idTree]]);
	}
		
	/**
	 * Главная страница отдела
	 * 1. Поиск указанного отдела (с id = $id) для просмотра настроек отдела
	 * 1.1. Если не найдно, то вызывается исключение CHttpException(404)
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
	 * @see FileHelper
	 * @see File
	 * @see Image
	 * @see News
	 * @see Department
	 * @uses actionView()
	 */
	private function showDepartment($id)
	{
	    // отображение новости
	    if ($this->model->general_page_type == Department::GP_SHOW_FIRST_NEWS)
	    {           
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
	               'files'=>File::filesForDownload($modelFirstNews[0]['id'], 'news'),
	               'images'=>Image::imagesForDownload($modelFirstNews[0]['id'], 'news'),
	               'breadcrumbsTreePath'=>[$modelFirstNews[0]['title']],
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
                   'files'=>File::filesForDownload($modelFromListNews['id'], 'news'),
                   'images'=>Image::imagesForDownload($modelFromListNews['id'], 'news'),
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
	    
	    return $this->render('noData', ['model'=>$this->model]); // представление с информацией, что нет данных
	}
	
	/**
	 * Подразделы отдела
	 * @param int $idTree идентификатор структуры
	 * @param int $idDepartment идентификатор отдела
	 * @param boolean $flagStruct 
	 * @see CHtml
	 * @return string
	 * @author oleg
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
	 * Данные рейтинга
	 * @param int $id идентификатор рейтинга
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
	 * Структура отдела
	 * Поиск осушествляется по идентификатору $id
	 * @param int $id идентификатор
	 * @author oleg
	 */
	public function actionStruct($id)
	{
		$model = $this->loadModel($id);
		$this->loadMenu($model);
		$this->render('struct', ['model'=>$model, 'arrayCard'=>$this->structCards($id)]);
	}
	
	/**
	 * @param int $id идентификатор
	 * @todo delete
	 * @deprecated
	 */
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
	 * @param int $id идентификатор отдела
	 * @return array
	 * @see DepartmentCard
	 * @uses actionStruct()
	 */
	private function structCards($id)
	{
		$arrayCard = array();
		$modelCard = DepartmentCard::model()->findAll('id_department=:id', [':id'=>$id]);

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
	 * @see Department
	 * @see Menu
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
		    Menu::$leftMenuAdd = array_merge(Menu::$leftMenuAdd, ($model->use_card ? [['name'=>'---','link'=>'#']] : []), $menu);
		}		
	}
	
	
	/**
	 * Для рейтингов отдела
	 * @param int $idDepartment идентификатор отдела
	 * @param int $idTree идентификатор структуры
	 */
	private function render_ratingData($idDepartment, $idTree)
	{
		$model = $this->loadModelRatingMainByTreeId($idTree);
		$modelDepartment = $this->loadModel($idDepartment);
		$modelTree = $this->modelTree;
		$this->pageTitle = $modelDepartment->department_name . ': Рейтинг';
		$this->render('rating', [
			'model'=>$model, 						
			'modelTree'=>$modelTree,	
		    'breadcrumbs'=>array(
		        'Отделы' => array('department/index'),
		        $modelDepartment->concatened => array('department/view', 'id'=>$modelDepartment->id),
		        $modelTree->name,
		    ),
		]);
	}
	
	

	
}
