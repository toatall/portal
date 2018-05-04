<?php

/**
 * This is the model class for table "{{module}}".
 *
 * The followings are the available columns in table '{{module}}':
 * @property string $name
 * @property string $description
 * @property boolean $only_use
 * @property integer $date_create
 * @property string $author
 * @property string $log_change 
 * @property boolean $children_node
 * @property string dop_action 
 * @property boolean dop_action_right_admin
 */
class Module extends CActiveRecord
{
	/**
	 * Доступ пользователей к модулю
	 * @var array
	 */
	public $permissionUser;
	
	/**
	 * Доступ групп к модулю
	 * @var array
	 */
	public $permissionGroup;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{module}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description', 'required'),			
			array('name', 'length', 'max'=>50),
            array('log_change', 'length', 'max'=>5000),
			array('description, author', 'length', 'max'=>250),
			array('dop_action, author', 'length', 'max'=>250),
            array('only_one, children_node, dop_action_right_admin', 'numerical', 'integerOnly'=>true),            
			// The following rule is used by search().			
			array('name, description, author, log_change, date_create, only_one, children_node, dop_action, dop_action_right_admin', 
					'safe', 'on'=>'search'),
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
			'users' => array(self::HAS_MANY, 'AccessModuleUser', 'module_name'),
			'groups' => array(self::HAS_MANY, 'AccessModuleGroup', 'module_name'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Наименование',
			'description' => 'Описание',
			'date_create' => 'Дата создания',
			'author' => 'Автор',
            'only_one' => 'Только для одного раздела',
            'log_change' => 'Журнал изменений',
			'children_node' => 'Разрешить подразделы',
			'dop_action' => 'Дополнительные настройки (действие)',
			'dop_action_right_admin' => 'Разрешить выполнять дополнительные настройки только администратору',
			'permissionUser'    => 'Пользователи',
			'permissionGroup'   => 'Группы',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date_create',$this->date_create);
		$criteria->compare('author',$this->author);                
        $criteria->compare('only_one',$this->only_one);
        $criteria->compare('children_node',$this->children_node);
        $criteria->compare('dop_action',$this->dop_action,true);
        $criteria->compare('dop_action_right_admin',$this->dop_action_right_admin);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Module the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
	
	/**
	 * Event before save
	 * {@inheritDoc}
	 * @see CActiveRecord::beforeSave()
	 */
    protected function beforeSave()
    {
        if ($this->isNewRecord) 
        {
            $this->date_create = new CDbExpression('getdate()');
            $this->author = Yii::app()->user->name;
        }
        $this->log_change = LogChange::setLog($this->log_change, 
            ($this->isNewRecord ? 'создание' : 'изменение'));            
        
        return parent::beforeSave();        
    }
    

    /**
     * Event after find
     * {@inheritDoc}
     * @see CActiveRecord::afterFind()
     */
    protected function afterFind()
    {
        $this->date_create = date('d.m.Y H:i:s', strtotime($this->date_create));
        parent::afterFind();
    }
    
    
    /**
     * Получение групп, имеющих доступ к модулю
     * 
     * @return array
     * @author oleg
     */
    public function getRightGroup()
    {
    	if ($this->isNewRecord)
    		return array();
    	
    	return CHtml::listData(Yii::app()->db->createCommand()
    				->select('group.id, group.name')
    				->from('{{access_module_group}} t')
    				->join('{{group}} group', '[t].[id_group] = [group].[id]')
    				->where('module_name=:name', [':name'=>$this->name])
    				->queryAll(), 'id', 'name');
    	
    }
    
    /**
     * Получение пользователей, имеющих доступ к модулю
     * 
     * @return array
     * @author oleg
     */
    public function getRightUser()
    {
    	if ($this->isNewRecord)
    		return array();
    	
    	return CHtml::listData(Yii::app()->db->createCommand()
    				->select("[user].[id], case when [user].[username_windows] <> '' or [user].[username_windows] is not null then [user].[username_windows] else [username] end"
    					." + '(' + [profile].[name] + ')' [concatened]")
    				->from('{{access_module_user}} t')
    				->join('{{user}} user', '[t].[id_user] = [user].[id]')
    				->leftJoin('{{profile}} profile', '[user].[id]=[profile].[id]')
    				->where('module_name=:name', [':name'=>$this->name])
    				->queryAll(), 'id', 'concatened');
    }
    
    
    /**
     * Получение списка модулей в соотвествии с правами пользователя
     * @return NULL|Module
     */
    public static function listCurrentUser()
    {
    	if (Yii::app()->user->isGuest) return null;
    	
    	return self::model()->findAll();
    	/*
    	if (Yii::app()->user->admin)
    		return self::model()->findAll();
    	
    	return Yii::app()->db->createCommand()
    		->selectDistinct('m.name, m.description')
    		->from('{{module}} m')
    		->leftJoin('{{access_module_group}} group', '[group].[module_name]=[m].[name]')
    		->leftJoin('{{access_module_user}} user', '[user].[module_name]=[m].[name]')
    		->leftJoin('{{group_user}} gp', '[group].[id_group]=[gp].[id_group]')
    		->where('[user].[id_user] = ' . Yii::app()->user->id . ' or [gp].[id_user]=' . Yii::app()->user->id)
    		->queryAll();
        */
    }
    
}
