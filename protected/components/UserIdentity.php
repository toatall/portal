<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 * @author alexeevich
 */
class UserIdentity extends CUserIdentity
{
	
	/**
	 * Идентификатор пользователя
	 * @var int
	 */
    private $_id;
    
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
	 * @param string $roleAdmin если пользователь обладает ролью админа
	 * @return array
	 * @uses authenticate()
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
	 * Получение идентификатора пользователя
	 * @return int	 
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
     * @return string
     */
    public function getReturnUrl()
    {
        $controller = Yii::app()->controller;
        $url = Yii::app()->createUtl($controller->module->id.'/'
            .$controller->module->defaultController.'/'.$controller->module->defaultAction);
        return $this->getState('_returnUrl', $url);
    }
       
}