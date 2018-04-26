<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	
	
    private $_id;
    
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
    
    /**
     * Если пользоваеть не существует, то создаем его в таблице
     * @return boolean
     */
	public function authenticate()
	{	 
		$model = User::findCreatePerson($this->username);
		
		if ($model!==null)
		{
			$this->_id = $model->id;
			$this->setState('admin', $model->role_admin);
			$this->setState('username_fio', (isset($model->fio)	? $model->fio : ''));
			$this->setState('isUFNS', $this->getIsUFNS($model->default_organization));
			$this->setState('guest', false);
			$this->setState('roles', $this->getRoles($model->role_admin));
			
			$_SESSION['folder_path'] = $model->folder_path;
		
		}
		else
		{
			$this->setState('roles', array());
			$this->setState('admin', false);
			$this->setState('guest', true);			
		}			
		
		$this->errorCode = self::ERROR_NONE;
		
		return true;
	}
		
	
	/**
	 * Возвращает список ролей пользователя
	 * @param string $roleAdmin - если пользователь обладает ролью админа
	 * @return string[]
	 * @author oleg
	 * @version 28.02.2017
	 */	
	private function getRoles($roleAdmin=false)
	{
		$roles = array();
			
		$modelRoles = Yii::app()->db->createCommand()
			->from('{{roles}} r')
			->join('{{roles_assignment}} r_a', 'r.name=r_a.itemname')
			->join('{{user}} u', 'u.id=r_a.userid')
			->queryAll();
		
		foreach ($modelRoles as $role)
		{
			$roles[] = $role['name'];
		}

		if ($roleAdmin)
		{
			$roles[] = 'admin';
		}
		
	
		return $roles;
	
	}
	
	
	
	
	/**
	 * Приведение логина из REGIONS\UserLogin в UserLogin
	 * @param string $username
	 * @return string|NULL
	 * @author oleg
	 */
	/*public function extractLogin($username)
	{
		if (preg_match('/(?<=\\)\S+/', $username, $matches))
		{
			if (isset($matches[0]))
			{
				return $mathes[0];
			}
		}
		return null;
	}*/
    
	
	/**
	 * Получение ID пользователя
	 * @author oleg	 
	 */
    public function getId()
    {
        return $this->_id;
    }
    
    
    /**
     * Проверяем, что пользователь из Управления
     * @return boolean
     */
    private function getIsUFNS($org)
    {
    	return (substr($org, 2, 2) == '00');
    }
        
    
    /**
     * Переадресация на домшнюю страницу модуля
     * @author oleg
     */
    public function getReturnUrl()
    {
        $controller = Yii::app()->controller;
        $url = Yii::app()->createUtl($controller->module->id.'/'
            .$controller->module->defaultController.'/'.$controller->module->defaultAction);
        return $this->getState('_returnUrl', $url);
    }
    
    
    
 
    
    
}