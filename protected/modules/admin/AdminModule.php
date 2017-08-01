<?php

class AdminModule extends CWebModule
{
    
    //public $homeUrl = '/admin/';
    
	public $errorLogin = false; // если есть ошибки при входе
    
	public function init()
	{
        parent::init();
               
        //Yii::app()->theme = 'bootstrap-admin';
        
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
               
        
        Yii::app()->setComponents(array(
            'errorHandler'=>array(
                'errorAction'=>'/admin/default/error',
            ),
        ));               
        
        
       Organization::loadCurrentOrganization(); 
        
        
	}

	public function beforeControllerAction($controller, $action)
	{
		
		if (parent::beforeControllerAction($controller, $action))
		{		
			
			if (!Yii::app()->user->isGuest && empty(Yii::app()->session['organization']))
				$this->errorLogin = 'Вам не назначен налоговый орган. Пожалуйста, обратитесь к администратору.';
			// this method is called before any module controller action is performed
			// you may place customized code here
			
			return true;
		}
		else
			return false;
	}
	
	
}
