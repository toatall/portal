<?php

class AccessController extends AdminController
{
    
    public $defaultAction = 'admin';
    
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
				'users'=>array('*'),
			),			            
		);
	}

	
	
	public function actionGetAccessGroupOrganization($group_id, $model_name, $model_id)
	{
	    // 1 имеет ли доступ текущий пользователь к этому разделу?
	    
	    // 2 вернуть список организаций 
	}
         
    
	public function actionSetAccessGroupOrganization($group_id, $model_name, $model_id, $id_organization, $check)
	{
	    // 1 имеет ли доступ текущи..
	    
	    // изменить данные о 
	}
    
}
