<?php

/**
 * Расширение класса пользователя
 * @author alexeevich
 * @see CWebUser
 */
class WebUser extends CWebUser
{
	
	/**
	 * Класс вызывается при каждом обовлении страницы
	 * {@inheritDoc}
	 * @see CWebUser::init()
	 */
	public function init()
	{
		parent::init();
		$this->updateLastAction();
	}
	
	/**
	 * Обновление времени последней активности пользователя
	 * Для реализации статистики в части просмортра текущих пользователей на сайте
	 * @uses init()	
	 */
	private function updateLastAction()
	{
		$lastId = (isset(Yii::app()->user->lastLoginId) ? Yii::app()->user->lastLoginId : null);
		if ($lastId===null || !is_numeric($lastId))
			return;
		Yii::app()->db->createCommand()
			->update('{{log_authenticate}}', [
				'last_action' => new CDbExpression('getdate()'),
			], 'id=:id', [':id'=>$lastId]);
	}

	public function getModel()
    {
        return User::model()->findByPk(Yii::app()->user->id);
    }
		
	/**
	 * Проверка, состоит ли пользователь в указанных группах
	 * Группу можно указать одну как строку, либо несколько как массив
	 * @param array|string $roles роли
	 * @return boolean
	 */
	public function inRole($roles)
	{		
		if (!is_array($roles))
			$roles = [$roles];
		
		if (Yii::app()->user->isGuest || !isset(Yii::app()->user->roles))
			return false;
	
		foreach ($roles as $role)
		{
			if (in_array($role, Yii::app()->user->roles))
				return true;
		}
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 * @see CWebUser::login()
	 */
	public function login($identity, $duration=0)
	{		    
	    $this->saveLogOpertaion('login');
	    return parent::login($identity, $duration);
	}
		
	/**
	 * {@inheritDoc}
	 * @see CWebUser::logout()
	 */
	public function logout($destroySession=true)
	{
		$this->saveLogOpertaion('logout');
		return parent::logout($destroySession);
	}
		
	/**
	 * Сохранение информации об аутентификации пользователя (вход и выход)
	 * @param string $operation
	 * @uses login()
	 * @uses logout()
	 */
	private function saveLogOpertaion($operation)
	{
		Yii::app()->db->createCommand()
			->insert('{{log_authenticate}}', array(
				'username'=>UserInfo::inst()->userLogin,
				'operation'=>$operation,
				'session_id'=>session_id(),
				'remote_ip_address'=>UserInfo::inst()->clientIP,
				'remote_host_name'=>UserInfo::inst()->clientHost,
				'browser_name'=>Yii::app()->browser->getBrowser(),
				'browser_version'=>Yii::app()->browser->getVersion(),
				'client_platform'=>Yii::app()->browser->getPlatform(),
				'agent_str'=>Yii::app()->browser->getUserAgent(),
				'date_create'=>new CDbExpression('getdate()'),
			));
		$this->setState('lastLoginId', Yii::app()->db->getLastInsertID());
	}
	
	/**
	 * Получить список доступных текущему пользователю групп
	 * @see Group
	 * @return number|array
	 */
	public function getUserGroupsId()
	{
	    if (Yii::app()->user->isGuest)
	        return 0;
	    return CHtml::listData(Group::model()->with('groupUsers')->findAll('groupUsers.id=:id_user', 
	        [
	            ':id_user'=>Yii::app()->user->id,	            
	        ]), 'id', 'id');
	}
		
	
	
}