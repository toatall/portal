<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string default_organization
 * @property string current_organization
 * @property string $username_windows
 * @property boolean $blocked
 * @property boolean $role_admin
 * @property string $date_create
 * @property string $date_edit
 * @property string $date_delete 
 * @property string $folder_path
 */
class User extends CActiveRecord
{
	
	const GUEST_NAME = 'Гость';
	
    public $password_repeat;
    public $_password_old;
    
    public $organizations=array();
    public $profile_name;
    
    public $users;
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username_windows', 'required'),            
            array('username', 'unique', 'attributeName'=>'username', 'className'=>'User'),
			array('username_windows', 'unique', 'attributeName'=>'username_windows', 'className'=>'User'),
			array('username, username_windows', 'length', 'max'=>250),
			array('password', 'compare', 'compareAttribute'=>'password_repeat'),
			array('folder_path', 'length', 'max'=>50),
			array('password, password_repeat', 'length', 'max'=>50),
			array('default_organization, current_organization', 'length', 'max'=>5),			
            array('id', 'numerical', 'integerOnly'=>true),
			array('blocked, role_admin', 'boolean'),			
			/*array('date_create, date_edit, date_delete', 'date', 
				'allowEmpty'=>true, 'format'=>'dd.MM.yyyy hh:mm:ss'),*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, username_windows, date_create, date_edit, date_delete, blocked, 
				default_organizartion, current_organization, profile_name', 'safe', 'on'=>'search'),
			array('profile_name, users', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            
			'organization'=>array(self::MANY_MANY, 'Organization',
                '{{user_organization}} (id_user, id_organization)'),
				
			'profile' => array(self::BELONGS_TO, 'Profile', 'id'),
				
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'username' => 'Логин',		
			'password' => 'Пароль',
			'password_repeat' => 'Подтверждение пароля',
			'username_windows' => 'Windows-логин',
            'date_create' => 'Дата создания',			
			'date_edit' => 'Дата изменения',
			'date_delete' => 'Дата удаления',
			'blocked' => 'Блокировка',           
			'role_admin' => 'Роль админа',
            /*'current_organization'=>'Доступные ораганизации',*/
            'default_organization'=>'Организация',			
			'organizations' => 'Доступные ораганизации',	
			'folder_path' => 'Дополнительный каталог'
		);
	}
	

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($users = array(), $no_admin=null)
	{       
		// @todo Please modify the following code to remove attributes that should not be searched.
        $users = Yii::app()->request->getParam('users');
        
		$criteria=new CDbCriteria;
		
		$criteria->with = array('profile');				
		
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.username',$this->username,true);
		$criteria->compare('t.username_windows',$this->username_windows,true);
        $criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.date_edit',$this->date_edit,true);		
		$criteria->addCondition('t.date_delete is null');
		$criteria->compare('t.blocked',$this->blocked);
		$criteria->compare('role_admin',$this->role_admin);
        // $criteria->compare('current_organization',$this->current_organization);                
        $criteria->compare('t.default_organization', isset(Yii::app()->session['organization'])
        	? Yii::app()->session['organization'] : $this->default_organization);
        
        $criteria->compare('profile.name',$this->profile_name,true);
        
        if ($no_admin == null) { $no_admin = Yii::app()->request->getParam('no_admin'); }        
        $criteria->compare('t.folder_path',$this->folder_path,true);
        $criteria->addNotInCondition('t.id',$users);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.default_organization asc, t.username asc',
            ),
		));
	}
	
    
    public function searchForTree($users = array())
    {
        $users = Yii::app()->request->getParam('users');
        
        $criteria=new CDbCriteria;
        
        $criteria->with = array('profile');
        
        $criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);	
		$criteria->compare('username_windows',$this->username_windows,true);
        $criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('blocked',$this->blocked);
        $criteria->compare('current_organization',$this->current_organization);  
        $criteria->compare('t.folder_path',$this->folder_path,true);
        if (Yii::app()->user->admin)
        {
            $criteria->compare('default_organization',$this->default_organization);
        }
        else
        {
            $criteria->compare('default_organization',Yii::app()->session['organization']);   
        }
        
        $criteria->compare('profile.name',$this->profile_name,true);
        //$criteria->addNotInCondition('t.id',$users);
        $criteria->addNotInCondition('t.id',explode(',', $this->users));
        
        
        return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.default_organization asc, t.username asc',
            ),
		));        
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}            
    
	
	/**
	 * Событие перед сохранением в БД
	 * @return parent::beforeSave();
	 */
    protected function beforeSave()
    {        
        if ($this->isNewRecord)                   
            $this->date_create = new CDbExpression('getdate()');                   
        $this->date_edit = new CDbExpression('getdate()');                
        if ($this->date_delete == null)
        	$date_delete = new CDbExpression('null'); 
        
        if (empty($this->password) && empty($this->password_repeat))
        {
        	$this->password = $this->_password_old;
        }
        else
        {
        	$this->password = CPasswordHelper::hashPassword($this->password);        	
        }
        
        return parent::beforeSave();
    }   
    
     
    
    protected function afterSave()
    {
    	Log::insertLog($this);
    	return parent::afterSave();
    }
    
        
    /**
     * Сохранение связи пользователя со структурой организации
     * 
     * @param $orgs array
     * @param $user_id int
     * @param $role_admin boolean
     * 
     * @author oleg
     * @version 21.02.2017 - refactoring
     */ 
    public function saveRelationOrganizations($orgs, $user_id, $role_admin)
    {                
        $command = Yii::app()->db->createCommand();
        $command->delete('{{user_organization}}', 'id_user=:id', array(':id'=>$user_id));
        
        if (!empty($orgs) && !$role_admin)
        {
            foreach ($orgs as $val)
            {                     
                if (!is_numeric($user_id) || !is_numeric($val)) continue;
                $command->reset();
                $command->insert('{{user_organization}}', array(
                    'id_user'=>$user_id,
                    'id_organization'=>$val,
                ));
            }
        }
    }
      
        
    /*
    public function getAdminsName()
    {        	
    	if (Yii::app()->user->isGuest)
    		return array(null);
    	
    	return array(Yii::app()->user->admin ? Yii::app()->user->name : null);
    }
    */
    
    protected function afterFind()
    {
        if (!empty($this->organization)) {
            foreach ($this->organization as $val) {
                $this->organizations[]=$val->code;
            }
        }
                
        $this->date_create = ConvertDate::find($this->date_create);
        $this->date_edit = ConvertDate::find($this->date_edit);
        $this->date_delete = ConvertDate::find($this->date_delete);
        $this->_password_old = $this->password;
        
        parent::afterFind();
    }
    
    public function getDropDownList()
    {
        $result_array = array();
        $model = User::model()->findAll(array(
            'condition'=>'role_admin=0',
            'order'=>'last_name ASC, first_name ASC, middle_name ASC'
        ));
        foreach ($model as $val)
        {
            $result_array[$val->id] = $val->last_name.' '.$val->first_name.' '.$val->middle_name;
        }
        return $result_array;
    }
        
    
    /**
     * Проверка имеет ли доступ пользователь к требуемой орагнизации
     * 
     * @param string $organization
     * @return boolean
     * @author oleg
     * @version 17.05.2016
     */
    public static function checkRightOrganization($organization)
    {
    	if ($organization===null) return false;
    	
        if (Yii::app()->user->admin) return true;
        
        return self::model()
        	->with('organization')
        	->exists('organization.code=:code', array(':code'=>$organization));
    }
    
    
    /**
     * Изменение орагнищации для текущей сессии ['organization']
     * 
     * @author oleg
     * @version 17.05.2016
     */
    public static function changeOrganization($organization)
    {        
        if (Yii::app()->user->admin || self::model()
	        	->with('organization')
	        	->exists('organization.code=:code', array(':code'=>$organization)))
        {
            Yii::app()->session['organization'] = $organization;
            self::model()->saveCurrentOrganiztion(Yii::app()->user->id, $organization);
            return true;
        } 
        return false;        
    }
    
    
    /**
     * Созхранение информации о текущей организации в данных у пользователя
     * 
     * @param integer $idUser
     * @param string $codeOrganization
     * @return integer
     * 
     * @author oleg
     * @version 17.05.2016
     */
    private function saveCurrentOrganiztion($idUser, $codeOrganization)
    {
    	return Yii::app()->db->createCommand()
    		->update($this->tableName(), array(
    			'current_organization' => $codeOrganization,
    		), 
    		'id=:id', array(':id'=>$idUser));
    }
    
    
    /**
     * Список организаций доступных пользователю
     * 
     * @return Organization
     * 
     * @author oleg
     * @version 17.05.2016
     */
    public static function userOrganizations()
    {
    	$criteria = new CDbCriteria;
    	
    	if (!Yii::app()->user->admin)
    	{    		
    		$userModel = User::model()->with('organization')->findByPk(Yii::app()->user->id);
    		
    		$org = isset($userModel->organization) ? 
    			CHtml::listData($userModel->organization, 'code', 'code') : array();
    		
    		$criteria->addInCondition('code', $org);
    	}
    		    	
    	return Organization::model()->findAll($criteria);    	    	
    }
    
    
    // @todo delete function
    public function getListUserForPermission()
    {
        $criteria = new CDbCriteria;       
        if (!Yii::app()->user->admin)
        {
            $criteria->condition = 'home_no=:home_no';
            $criteria->params = array(':home_no'=>Yii::app()->session['organization']);
        }
        $criteria->order = 'last_name, first_name, middle_name';
                
        return new CActiveDataProvider(User::model()->find($criteria));                
    }
    
    
    /**
    @todo delete/refactor
     **/
    /*
    public function getUsersByTreeAccess($id_tree)
    {
        $record = Access::model()->with('user', 'profile')->findAll(array(
            'order'=>'[user].default_organization, [user].username',
            'condition'=>'t.id_tree=:id_tree AND is_group=0 AND t.id_organization=:id_organization',
            'params'=>array(
                ':id_tree'=>$id_tree,
            	':id_organization'=>Yii::app()->session['organization'],
            )
        ));
        $resultArray = array();
        
        foreach ($record as $value)
        {            
            $resultArray[$value->user->id] = (!empty($value->user->username_windows) ? 
            	$value->user->username_windows : $value->user->name)
            	. (isset($value->profile->name) ? ' (' . $value->profile->name . ')' : '');            	
        }
        return $resultArray;
    }*/
           
    
    
    /**
     * Сохранение нового пользователя в БД
     * 
     * @param string $username
     * @return User
     * 
     * @author oleg
     * @version 16.05.2016 - create
     * 			21.02.2017 - refactoring
     */
    public static function saveWindowsUser($username)
    {
    	
    	$modelOrganization = Organization::codeOrganizationByUsernameWindows($username);
    	$org = ($modelOrganization !== null) ? $modelOrganization->code : new CDbExpression('null');
    	
    	// сохранение нового пользователя
    	$model = new self();
    	$model->username_windows = $username;
    	$model->default_organization = $org;
    	$model->current_organization = $org;
    	$model->role_admin = false;
    	$model->blocked = false;
    	
    	if ($model->validate() && $model->save())
    	{
    		if ($modelOrganization !== null)
    		{
    			$model->saveRelationOrganizations(
    				array($modelOrganization->code => $modelOrganization->code),
    				$model->id, 
    				$model->role_admin
    			);
    		}
    		
    		// сохранение профиля
    		Profile::findSaveProfile($model);    		
    		
    		return $model;    		
    	}
    	return null;
    }
    
    
    public function getConcatened()
    {
    	return (!empty($this->username_windows) ? $this->username_windows : $this->username)
    		. ' (' . (isset($this->profile->name) ? $this->profile->name : ' ') . ')';
    }
    
    
    
    /**
     * Приведение логина из REGIONS\UserLogin в UserLogin 
     * @param string $username
     * @return string|NULL
     * @author oleg
     */
    public static function extractLogin($username)
    {
    	$u = explode("\\", $username);
    	    	
    	if (count($u)>1)
    	{
    		return $u[1];
    	}
    	
   		return null;
    }
    
    
    /**
     * Поиск пользователя в БД, если не найден то создается новый
     * @param string $username
     * @return User
     * @author oleg
     */
    public static function findCreatePerson($username)
    {
    	
    	//$username = self::extractLogin($username);
    	if ($username===null) return null; // если не удалось извлечь имени пользователя
    	
    	// пытаемся найти пользователя
    	$model = self::model()->find(
    		'username_windows=:username', array(
    			':username' => $username,
    	));
    	
    	// если нет такого пользователя, то создаем его
    	if ($model === null)
    	{
    		$model = self::saveWindowsUser($username);
    	}
    	
    	return $model;
    	
    }
    
    
    public static function profileByLogin($login)
    {
    	$username = $login;
    	$photo = '/images/user-nophoto.png';
    	
    	$model = self::model()->find('username_windows=:username',[':username'=>$login]);
    	if ($model!==null && isset($model->profile->name))
    	{
    		$username = $model->profile->name;
    		if ($model->profile->photo_file != null && file_exists(Yii::app()->basePath . '\..\images\profiles\\' . $model->profile->photo_file))
    		{
    			$photo = '/images/profiles/' . $model->profile->photo_file;
    		}
    	}    	
    	return Yii::app()->controller->renderFile(dirname(__FILE__) . '/../views/profile/info.php', array('username'=>$username, 'photo'=>$photo));
    }
        
    
    
}
