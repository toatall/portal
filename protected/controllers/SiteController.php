<?php

class SiteController extends Controller
{
	
	/**
	 * {@inheritDoc}
	 * @see CController::filters()
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
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('index', 'browsers', 'telephones', 'telephoneDownload', 'contact', 'captcha', 'error'),
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
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$model = new NewsSearch();
		$this->render('index', [
		    'modelUFNS'=>$model->feedNewsDay,
			'modelIFNS'=>$model->feedIFNS,
			'modelPressClub'=>$model->feedDopNews('PressClub'),
			'modelDosug'=>$model->feedDopNews('Dosug'),			
			'modelHumor'=>$model->feedDopNews('Humor'),
		]);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->pageTitle = Yii::app()->name . ' - Ошибка';
		
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			$model->name = UserInfo::inst()->userLogin;
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <portal-contact@u8600-app045.regions.tax.nalog.ru>\r\n".
					"Reply-To: portal-contact@u8600-app045.regions.tax.nalog.ru\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/html; charset=UTF-8";
				
				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Сообщение успешно отправлено!');
				//$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	
    
    
    public function actionBrowsers()
    {
    	$this->pageTitle = 'Рекомендуемые браузеры';    	
        $this->render('browsers');
    }
	
    
    
    /**
     * Список телефонных справочников организаций
     */
    public function actionTelephones()
    {
        $this->pageTitle='Телефонные справочники';
        $model = new Telephone('search');        
        $this->render('telephones', array('model'=>$model));
    }          
       
   
	public function actionTelephoneDownload($id)
   	{
   		$model = Telephone::model()->findByPk($id);
   		if ($model===null)
   			throw new CHttpException(404,'Страница не найдена.');
   		if (!$model->downloadFile())
   			throw new CHttpException(404,'Страница не найдена.');
   	}
   
   	
    
	
}