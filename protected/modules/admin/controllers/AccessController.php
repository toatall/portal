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
	
	
	/**
	 * Список групп для указанного объекта доступа
	 * @param string $model_name
	 * @param int $model_id
	 * @param string $id_organization
	 * 
	 * @version 03.10.2017
	 */
	public function actionAccessObjectGroup($model_name, $model_id, $id_organization)
	{
	    if (!$this->checkAccessCurrentUser($model_name, $model_id, $id_organization))
	        throw new CHttpException(403,'Доступ запрещен.');
	    
	    $model = Access::model()->with('groups')->findAll('t.access_mode=:access_mode and t.model_name=:model_name 
            and t.model_id=:model_id and t.id_organization=:id_organization',
	        array(
	            ':access_mode'=>'group',
    	        ':model_name'=>$model_name,
    	        ':model_id'=>$model_id,
    	        ':id_organization'=>$id_organization,
	    ));
	    
	    $this->renderPartial('groups', array(
	        'model'=>$model,	        
	    ));
	    
	}

	
	
	public function actionGetAccessGroupOrganization($group_id, $model_name, $model_id)
	{
	    if (!$this->checkAccessCurrentUser($model_name, $model_id, $id_organization))
	        throw new CHttpException(403,'Доступ запрещен.');
	    
	    // 2 вернуть список организаций 
	    $this->renderPartial('listOrganization');
	}
         
    
	public function actionSetAccessGroupOrganization($group_id, $model_name, $model_id, $id_organization, $check)
	{
	    if (!$this->checkAccessCurrentUser($model_name, $model_id, $id_organization))
	        throw new CHttpException(403,'Доступ запрещен.');
	    
	    // изменить данные о 
	}
	
	
	/**
	 * Проверка доступа пользователю для доступа к данным о доступе! :)
	 * @param string $model_name
	 * @param int $model_id
	 * @param string $id_organization
	 * @return boolean
	 */
	private function checkAccessCurrentUser($model_name, $model_id, $id_organization)
	{
	    if (Yii::app()->user->isGuest)
	        return false;
	    
	    if (Yii::app()->user->admin)
	        return true;
	    
	    return Access::model()->findAccessObjectCurrentUserGroup($model_name, $model_id, $id_organization);
	}
    
}
