<?php

class DefaultController extends AdminController
{            
    
    public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
        
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
				$url = /*!empty(Yii::app()->user->returnUrl) ? Yii::app()->user->returnUrl :*/['/site/index'];
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
		
		
		// @todo переадресация на страницу ошибки аутентефикации		
		
		
		/*		
		$model=new LoginForm;
		
		if (!isset($_SESSION['auth_login']) || empty($_SESSION['auth_login']) || ($_SESSION['auth_login'] == 'guest'))
		{
			$this->render('error_login');
			Yii::app()->end();
		}
		
		$model->username = $_SESSION['auth_login'];
		if ($model->login())
		{
			$url = ($url!==null) ? $url : ['/admin/default/index'];
			$this->redirect($url);
		}
		
		$this->render('error_login');
		*/
		
	}
    
    /**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('/admin/default/login'));
	}
    
	
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
     * @param code string - код НО
     */ 
    public function actionChangeCode($code) 
    {
        if (!isset($code) || !is_numeric($code)) 
            throw new CHttpException(400, "Неверный запрос. Не указан код налогового органа!");
        
        if (!Organization::model()->exists('code=:code', array(':code'=>$code)))
            throw new CHttpException(400, "Неверный запрос. Указан не существующий код налогового органа!");         
        
        if (!User::checkRightOrganization($code))
        {
            //throw new CHttpException(403, "Вам запрещен доступ к данному налоговому органу!");
            $this->render('default/error_login');
            Yii::app()->end();
        }
        
        User::changeOrganization($code);
        
        DefaultController::redirect(Yii::app()->request->urlReferrer);
    }
    
    
    
    
    /** ФНКЦИИ ДЛЯ ГЛАВНОЙ СТРАНИЦЫ **/
    /*
    public function treeGeneralPage($id_parent=0)
    {        
        $data = array();
        $orgData = Tree::model()->findAll(array(
            'order'=>'sort ASC, name ASC',
            'condition'=>'id_parent=:id_parent AND id<>:id', 
            'params'=>array(':id_parent'=>$parent_id, ':id'=>$id)            
        ));
        foreach ($orgData as $value)
        {                             
            $data[] = array(
                'id'=>$value->id, 
                'text'=>'<i class="icon-folder-open"></i>&nbsp;'
                    .$value->name.'&nbsp'
                    .CHtml::link('<i class="icon-eye-open"></i>', 
                        array('view', 'id'=>$value->id),
                        array('class'=>'view', 'data-original-title'=>'Просмотреть', 'rel'=>'tooltip')).'&nbsp'
                    .CHtml::link('<i class="icon-pencil"></i>', 
                        array('update', 'id'=>$value->id),
                        array('class'=>'update', 'data-original-title'=>'Редактировать', 'rel'=>'tooltip')).'&nbsp'
                    .CHtml::link('<i class="icon-trash"></i>', 
                        '#', 
                        array(
                            'submit'=>array('delete', 'id'=>$value->id),
                            'confirm'=>'Вы уверены что хотите удалить "'.$value->name.'"? Все дочерние подразделы будут удалены!',
                            'class'=>'delete',
                            'data-original-title'=>'Удалить',
                            'rel'=>'tooltip',
                        )
                    ),
                'children'=>$this->getTree($id, $value->id),
            );
        }
        return $data;
        
    }            */
    
    
    public function actionAjaxSections()
    {
        if (!isset($_POST['org'])) {
            echo 'Нет параметра org!';
        }
        if (trim($_POST['org']) == '') {
            echo 'Параметр org пустой!';
        }
    }
    
    
    
    public function actionHelp()
    {
        $this->render('help');
    }
    
}