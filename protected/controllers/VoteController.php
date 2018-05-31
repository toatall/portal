<?php

/**
 * Голосование
 * @author alexeevich
 */
class VoteController extends Controller
{
	
	/**
	 * {@inheritDoc}
	 * @see CController::filters()
	 * @return array
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see CController::accessRules()
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('index', 'current', 'view', 'button'),
				'users' => array('@'),
			),
			array(
				'actoins' => array('error'),
				'users' => array('?'),
			),
			array('deny'),
		);
	}
		
   	/**
   	 * Поиск доступных голосований 
   	 * Для главной сраницы
   	 */
   	public function actionCurrent()
   	{   	    
   	    $model = Yii::app()->db->createCommand()
   	        ->from('{{vote_main}}')
   	        ->where('on_general_page=1')
   	        ->andWhere('date_start<=getdate() and date_end>=getdate()')
   	        ->andWhere('organizations like :organization', [':organization'=>'%'.UserInfo::inst()->organizationFromLogin().'%'])
   	        ->queryAll();
   	    echo $this->renderPartial('current', ['model'=>$model]);    	       	   
   	}
   	
   	/**
   	 * Показать все голосования
   	 * И текущие и завершенные (кроме еще не начавшихся)
   	 */
   	public function actionIndex()
   	{
   	    $model = VoteMain::model()->findAll('organizations like :organization', [':organization'=>'%'.UserInfo::inst()->organizationFromLogin().'%']);
   	    $this->render('index', ['models'=>$model]);
   	}
   	
   	/**
   	 * Вопросы для голосования
   	 * @param integer $id идентификатор голосования
   	 * @throws CHttpException
   	 * @return string
   	 */
   	public function actionView($id)
   	{
   	    $modelMain = VoteMain::model()->findByPk($id, 'organizations like :organization', [':organization'=>'%'.UserInfo::inst()->organizationFromLogin().'%']);
   	    
   	    if ($modelMain===null)
   	        throw new CHttpException(404);
   	    
   	    
   	    if (isset($_GET['vote']))
   	    {
   	        // сохранение голоса, только если не голосовал
   	        if (!$modelMain->isVoted)
   	            VoteQuestion::saveAnswer($_GET['vote']);
   	    }
   	    
   	    $modelQuestion = VoteQuestion::model()->findAll('id_main=:id_main', [':id_main'=>$id]);
   	      
   	    if (isset($_GET['vote']))
   	    {
   	        echo $this->renderPartial('view', [
   	            'modelMain'=>$modelMain,
   	            'modelQuestion'=>$modelQuestion,
   	        ], true, true);
   	    }
   	    else
   	    {   	     	        
       	    echo CJSON::encode([
       	        'title'=>$modelMain->name,
       	        'content'=>$this->renderPartial('view', [
       	            'modelMain'=>$modelMain,
       	            'modelQuestion'=>$modelQuestion,
       	        ], true, true),
       	    ]);
   	    }
   	}
   	
   
}