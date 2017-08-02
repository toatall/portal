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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */	
	public function actionView($id, $idTree=null)
	{
		
		$this->model = $this->loadModel($id);
		$this->loadMenu($this->model);
		
		if ($idTree!=null)
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
				$dirs = FileHelper::fileImageDirByNewsId($modelNews[0]->id);
				$render['name'] = 'view';
				$render['vars'] = array(
					'model'=>$this->model,
					'modelNews'=>$modelNews,
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
				
		$this->renderPartial('ratingMain', [
			'model'=>$model,
			'modelYear'=>$this->loadModelRatingDataYears($model->id),
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
	 * @param unknown $idDepartment
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
