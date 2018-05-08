<?php

/**
 * Default controller
 * @author alexeevich
 */
class DefaultController extends AdminController
{            
    /**
     * Perform access control for CRUD operations
     */
    public function filters()
	{
		return array(
			'accessControl',
		);
	}
      
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
    public function accessRules()
	{
		return array(
            array('allow',
                'actions'=>array('index', 'ajaxSections', 'changeCode', 'logout'),
                'users'=>array('@'),
            ),
            array('allow',
                'actions'=>array('login', 'error', 'help'),
                'users'=>array('*'),
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
           
	/**
	 * Страница по-умолчанию (главная страница)
	 */
	public function actionIndex()
	{	        
		$this->render('index');		
	}
    	
    /**
	 * Аутентефикация пользователя
	 * @param string $url
	 * @see User
	 * @see UserIdentity
	 */
	public function actionLogin($url=null)
	{			
		$username = null;
		
		if (isset($_SERVER['AUTH_USER']) && !empty($_SERVER['AUTH_USER']))
		{
			$username = User::extractLogin($_SERVER['AUTH_USER']);
		}
		
		if ($username === null)
			$username = User::GUEST_NAME;
				
		$identity = new UserIdentity($username,'');
		
		if ($identity->authenticate())
		{
			if (Yii::app()->user->login($identity))
			{								
				$url = !empty(Yii::app()->user->returnUrl) ? Yii::app()->user->returnUrl :['/site/index'];
				$this->redirect($url);
			}
			else 
			{
				echo 'User not login!';
				var_dump($identity);
			}
		}
		else
		{
			$this->render('error_login');
		}			
	}
    
    /**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('/admin/default/login'));
	}
    
	/**
	 * Страница вывода информации об ошибке
	 */
    public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
    
    /** 
     * Изменение кода НО и редирект на прошлую страницу
     * @param code string код организации
     * @throws CHttpException
     */ 
    public function actionChangeCode($code) 
    {
        if (!isset($code) || !is_numeric($code)) 
            throw new CHttpException(400, "Неверный запрос. Не указан код налогового органа!");
        
        if (!Organization::model()->exists('code=:code', array(':code'=>$code)))
            throw new CHttpException(400, "Неверный запрос. Указан не существующий код налогового органа!");         
        
        if (!User::checkRightOrganization($code))
        {           
            $this->render('default/error_login');
            Yii::app()->end();
        }        
        User::changeOrganization($code);        
        self::redirect(Yii::app()->request->urlReferrer);
    }
    
    /**
     * @deprecated что это?
     */
    public function actionAjaxSections()
    {
        throw new CHttpException(410);
        if (!isset($_POST['org'])) {
            echo 'Нет параметра org!';
        }
        if (trim($_POST['org']) == '') {
            echo 'Параметр org пустой!';
        }
    }
    
    /**
     * Справка
     */
    public function actionHelp()
    {
        $this->render('help');
    }
    
}