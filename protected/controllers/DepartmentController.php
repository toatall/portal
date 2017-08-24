<?php


class DepartmentController extends Controller
{
	
	/**
	 * Use modules
	 * @var array
	 */
	private $useModules = array(
		'ratingData',
	);
	
	private $model = null;
	private $modelTree = null;
	
	
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
	 * 
	 * 1. Если присутсвует (и существует) $idTree и в текущем классе имеется
	 * функция с именем "render_$module$", где $module$ - имя модуля (Tree->module)
	 * то вызываем эту функцию и передаем ей управление (изначально было задумано так
	 * для рейтингов, но может пригодится где-то еще)$this
	 * 
	 * 2. Если не указан $idTree, то смотрим в настройки отдела.
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
	 * Где Department - это модель текущего отдела (скорее всего так - $this->model = $this->loadModel($id))
	 * 
	 * @author oleg
	 * @version 22.08.2017
	 * 
	 */	
	public function actionView($id, $idTree=null)
	{
		
		$this->model = $this->loadModel($id);
		$this->loadMenu($this->model);
		
		if ($idTree!=null && is_numeric($idTree))
		{
			$this->modelTree = $this->loadModelTree($idTree);
			$methodName = 'render_' . $this->modelTree->module;
			if (in_array($this->modelTree->module, $this->useModules) && method_exists($this, $methodName))
			{						
				return call_user_func(array($this, $methodName), $id, $idTree);
			}
		}
		
		
		// 1. Если включена структура отдела, 
		// то вывести на главной структуру (фотки, фамилии) 
		// (если не укзан раздел дерева, т.е. не выполнен переход в какой-либо раздел
		if ($idTree === null && $this->model->use_card)
		{
			$render['name'] = 'struct';
			$render['vars'] = array(
				'model'=>$this->model,
				'arrayCard'=>$this->structCards($id),
			);		
		}
		else
		{		
			// 2. Если указан раздел, то показывать новости этого раздела
			// 2.1. Если новость 1 в разделе, то сразу ее открываю
			$tree = ($idTree===null) ? $this->model->id_tree : $idTree;
			$modelNews = News::model()->findAll('id_tree=:id_tree and date_delete is null and flag_enable=1 and date_start_pub < getdate() and date_end_pub > getdate()', [':id_tree'=>$tree]);
			
			
			$modelNewsSearch = new NewsSearch('search');
			
			if(isset($_GET['News']))
				$modelNewsSearch->attributes=$_GET['News'];
				$modelNewsSearch->id_tree = $tree;
			
			
			if (count($modelNews) == 1 && isset($modelNews[0]) && $modelNews[0] instanceof News)
			{
				$dirs = FileHelper::fileImageDirByNewsId($modelNews[0]->id, $modelNews[0]->id_organization);
				$render['name'] = 'view';
				$render['vars'] = array(
					'model'=>$this->model,
					'modelNews'=>$modelNews,
				    'modelTree'=>$this->modelTree,
					'dirImage'=>$dirs['dirImage'],
					'dirFile'=>$dirs['dirFile'],
				);
			}
			else
			{
				$render['name'] = 'news';
				$render['vars'] = array(
					'model'=>$this->model,
					'modelNews'=>$modelNewsSearch,
				    'modelTree'=>$this->modelTree,
				);
			}			
		}		
		
		// 3. Папки показывать слева как меню, с учетов вложений		
		$this->render($render['name'], $render['vars']);
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
		foreach ($modelCard as $card)
		{
			$arrayCard[$card->user_level][] = [
				'user_photo' => $card->user_photo,
				'user_fio' => $card->user_fio,
				'user_rank' => $card->user_rank,
				'user_position' => $card->user_position,
				'user_telephone' => $card->user_telephone,
				'user_resp' => $card->user_resp,
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
		/*
		else
		{
			Menu::$leftMenuAdd = array_merge(Menu::$leftMenuAdd, 
				[['name'=>'Страницы отдела', 'link'=>['department/view', 'id'=>$model->id, 'idTree'=>$model->id_tree]]]);
		}
		*/
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
