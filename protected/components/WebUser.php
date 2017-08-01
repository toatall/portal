<?php


class WebUser extends CWebUser
{
	
	/**
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
	 * @author tvog17
	 * @version 27.07.2017
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
	
		
	/**
	 * @param array $roles
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
		$loginResult = parent::login($identity, $duration);
		$this->saveLogOpertaion('login');
		return $loginResult;
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
	 * @param unknown $operation
	 */
	private function saveLogOpertaion($operation)
	{
		Yii::app()->db->createCommand()
			->insert('{{log_authenticate}}', array(
				'username'=>$this->name,
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
		
	
	
}