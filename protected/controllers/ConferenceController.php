<?php

class ConferenceController extends Controller
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),           
		));
	}

	
	
	private function modelSearchConference($typeConference)
	{
		$model=new Conference('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Conference']))
			$model->attributes=$_GET['Conference'];
		$model->type_conference = $typeConference;
		$model->backendSide = false;
		return $model;
	}
	
	
	

	/**
	 * Собрания
	 * @author tvog17
	 */
	public function actionIndex()
	{		
		$model = $this->modelSearchConference(Conference::TYPE_CONFERENCE);		
		$this->render('index',array(
			'model'=>$model, 
		));		
	}
	
	
	
	/**
	 * ВКС с ФНС
	 * @author tvog17
	 */
	public function actionVksFns()
	{
		$model = $this->modelSearchConference(Conference::TYPE_VKS_FNS);		
		$this->render('index',array(
			'model'=>$model, 			
		));
	}
	
	
	
	/**
	 * ВКС с УФНС
	 * @author tvog17
	 */
	public function actionVksUfns()
	{
		$model = $this->modelSearchConference(Conference::TYPE_VKS_UFNS);
		$this->render('index',array(
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
		$model = Conference::model()->findByPk($id, 'date_delete is null'); 		       
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		return $model;
	}
	
	
	/**
	 * Собрания и видеоконференции на сегодня
	 */
	public function actionToday()
	{
		$modelConference = Conference::model()->cache(300)->findAll('type_conference=:type and convert(varchar,date_start,104)=:date and date_delete is null', [
			':type'=>Conference::TYPE_CONFERENCE,
			':date'=>date('d.m.Y'),			
		]);
		$modelVksFns = Conference::model()->cache(300)->findAll('type_conference=:type and convert(varchar,date_start,104)=:date and date_delete is null', [
			':type'=>Conference::TYPE_VKS_FNS,
			':date'=>date('d.m.Y'),			
		]);
		$modelVksUfns = Conference::model()->cache(300)->findAll('type_conference=:type and convert(varchar,date_start,104)=:date and date_delete is null', [
				':type'=>Conference::TYPE_VKS_UFNS,
				':date'=>date('d.m.Y'),
		]);
		$this->renderPartial('today', [			
			'modelConference'=>$modelConference,
			'modelVksFns'=>$modelVksFns,
			'modelVksUfns'=>$modelVksUfns,
		]);
	}
	
	
	public function actionProgress()
	{
		$this->render('progress');
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
