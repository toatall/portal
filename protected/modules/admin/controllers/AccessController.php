<?php

/**
 * Управление паравами
 * @author alexeevich
 * @see AdminController
 * @see Access
 */
class AccessController extends AdminController
{
    /**
     * Контроллер по умолчанию
     * @var string
     */
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
	 * @param string $model_name имя модели 
	 * @param int $model_id идентификатор модели
	 * @param string $id_organization код организации
	 * @see Access
	 * @deprecated
	 */
	public function actionAccessObjectGroup($model_name, $model_id, $id_organization)
	{
	    throw new CHttpException(410);
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
	
	/**
	 * @param int $group_id
	 * @param string $model_name
	 * @param int $model_id
	 * @throws CHttpException
	 * @deprecated
	 */
	public function actionGetAccessGroupOrganization($group_id, $model_name, $model_id)
	{
	    throw new CHttpException(410);
	    if (!$this->checkAccessCurrentUser($model_name, $model_id, $id_organization))
	        throw new CHttpException(403,'Доступ запрещен.');
	    // 2 вернуть список организаций 
	    $this->renderPartial('listOrganization');
	}
	
	/**
	 * 
	 * @param unknown $group_id
	 * @param unknown $model_name
	 * @param unknown $model_id
	 * @param unknown $id_organization
	 * @param unknown $check
	 * @throws CHttpException
	 * @deprecated
	 */
	public function actionSetAccessGroupOrganization($group_id, $model_name, $model_id, $id_organization, $check)
	{
	    throw new CHttpException(410);
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
	 * @uses self::actionSetAccessGroupOrganization()
	 * @uses self::actionGetAccessGroupOrganization()
	 * @uses self::actionAccessObjectGroup()
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
