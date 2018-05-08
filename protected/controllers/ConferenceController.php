<?php

/**
 * Собрания и ВКС (далее - конференция)
 * @author alexeevich
 * @see Conference
 */
class ConferenceController extends Controller
{
	
	/**
	 * Установка прав доступа к действиям
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
	 * Просмотр данных конференции
	 * @param integer $id the ID of the model to be displayed
	 * @uses $this->loadModel()
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),           
		));
	}
    
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 * @see Conference
	 * @exception CHttpException
	 */
	public function loadModel($id)
	{
	    $model = Conference::model()->findByPk($id, 'date_delete is null');
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
	
	/**
	 * Список собраний	
	 * @see Conference
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
	 * @see Conference
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
	 * @see Conference
	 */
	public function actionVksUfns()
	{
	    $model = $this->modelSearchConference(Conference::TYPE_VKS_UFNS);
	    $this->render('index',array(
	        'model'=>$model,
	    ));
	}
	
	/**
	 * Поиск конференции по виду (вкс, собрание)
	 * @see Conference
	 * @param int $typeConference
	 * @return Conference
	 * @uses actionVksUfns()
	 * @uses actionVksFns()
	 * @uses actionIndex()
	 */
	private function modelSearchConference($typeConference)
	{
	    $model=new Conference('search');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['Conference']))
	    {
	        $model->attributes=$_GET['Conference'];
	    }
        $model->type_conference = $typeConference;
        $model->backendSide = false;
        return $model;
	}
	
	
	/**
	 * Собрания и видеоконференции на сегодня
	 * @see Conference
	 */
	public function actionToday()
	{
		$modelConference = Conference::model()->findAll('type_conference=:type and convert(varchar,date_start,104)=:date and date_delete is null', [
			':type'=>Conference::TYPE_CONFERENCE,
			':date'=>date('d.m.Y'),
		]);
		$modelVksFns = Conference::model()->findAll('type_conference=:type and convert(varchar,date_start,104)=:date and date_delete is null', [
			':type'=>Conference::TYPE_VKS_FNS,
			':date'=>date('d.m.Y'),
		]);
		$modelVksUfns = Conference::model()->findAll('type_conference=:type and convert(varchar,date_start,104)=:date and date_delete is null', [
				':type'=>Conference::TYPE_VKS_UFNS,
				':date'=>date('d.m.Y'),
		]);
		$this->renderPartial('today', [
			'modelConference'=>$modelConference,
			'modelVksFns'=>$modelVksFns,
			'modelVksUfns'=>$modelVksUfns,
		]);
	}
	
	/**
	 * @deprecated
	 */
	public function actionProgress()
	{
		$this->render('progress');
	}
    
	
}
