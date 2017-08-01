<?php

class DefaultController extends Controller
{            
    
    public $layout = '/layouts/column2';
    
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
                'actions'=>array('index', 'ajaxSections', 'error', 'changeCode'),
                'users'=>array('@'),
            ),
            array('allow',
                'actions'=>array('login','logout'),
                'users'=>array('*'),
            ),
            array('deny',  // deny all users
				'users'=>array('*'),
                'deniedCallback'=>function(){ Yii::app()->controller->redirect(array('login')); },
			),			            
		);
	}
    
        
	public function actionIndex()
	{	        
		$this->render('index');
	}
    
    /**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
                $this->redirect(array('index'));
				//$this->redirect(Yii::app()->user->returnUrl);                
		}
		// display the login form
		$this->render('login',array('model'=>$model));
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
    
    
    /** ==+== Изменение кода НО и редирект на прошлую страницу **/
    public function actionChangeCode($code) 
    {
        if (!isset($code) || !is_numeric($code)) 
            throw new CHttpException(400, "Неверный запрос. Не указан код налогового органа!");
        if (!Organization::model()->exists('code=:code', array(':code'=>$code)))
            throw new CHttpException(400, "Неверный запрос. Указан не существующий код налогового органа!");         
        if (!User::checkNo($code))
            throw new CHttpException(401, "Вам запрещен доступ к данному налоговому органу!");
        User::changeNo($code);
        DefaultController::redirect(Yii::app()->request->urlReferrer);
    }
    
    
    
    
    /** ФНКЦИИ ДЛЯ ГЛАВНОЙ СТРАНИЦЫ **/
    
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
        
    }            
    
    
    public function actionAjaxSections()
    {
        if (!isset($_POST['org'])) {
            echo 'Нет параметра org!';
        }
        if (trim($_POST['org']) == '') {
            echo 'Параметр org пустой!';
        }
    }
    
}