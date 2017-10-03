<?php

/**
 * This is the model class for table "{{access}}".
 *
 * The followings are the available columns in table '{{access}}':
 * @property integer $id
 * @property string $access_mode
 * @property integer $access_identity
 * @property string $model_name
 * @property integer $model_id
 * @property string $id_organization
 * @property string $date_create
 * @property string $author
 */

class Access extends CActiveRecord
{
	    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{access}}';
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('access_mode, access_identity, model_name, model_id, id_organization, author', 'required'),
            array('access_identity, model_id', 'numerical', 'integerOnly'=>true),
            array('access_mode, model_name', 'length', 'max'=>50),
            array('id_organization', 'length', 'max'=>5),
            array('author', 'length', 'max'=>250),
            array('id, access_mode, access_identity, model_name, model_id, id_organization, 
                date_create, author', 'unsafe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, access_mode, access_identity, model_name, model_id, id_organization, 
                date_create, author', 'safe', 'on'=>'search'),
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
        );
    }
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'access_mode' => 'Access Mode',
            'access_identity' => 'Access Identity',
            'model_name' => 'Model Name',
            'model_id' => 'Model',
            'id_organization' => 'Id Organization',
            'date_create' => 'Date Create',
            'author' => 'Author',
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
        
        $criteria->compare('id',$this->id);
        $criteria->compare('access_mode',$this->access_mode,true);
        $criteria->compare('access_identity',$this->access_identity);
        $criteria->compare('model_name',$this->model_name,true);
        $criteria->compare('model_id',$this->model_id);
        $criteria->compare('id_organization',$this->id_organization,true);
        $criteria->compare('date_create',$this->date_create,true);
        $criteria->compare('author',$this->author,true);
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Access the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
	    
	
    
    
    /**
     * Проверка доступа к объекту $model_name->$model_id для пользователя с ИД $user_id
     * @param int $user_id
     * @param string $model_name
     * @param int $model_id
     * @param string $id_organization
     * @return boolean
     * 
     * @version 03.10.2017 - create
     */    
    public function findAccessObjectUsers($user_id, $model_name, $model_id, $id_organization=null)
    {
        if (Yii::app()->user->isGuest)
            return false;
        
        // если не указан код организации, то взять текущий код орагнизации из сессии
        if ($id_organization == null)
            $id_organization = Yii::app()->session['organization'];
        
        // поиск
        $criteria = new CDbCriteria();
        $criteria->compare('access_mode', 'user');
        $criteria->compare('access_identity', $user_id);
        $criteria->compare('model_name', $model_name);
        $criteria->compare('model_id', $model_id);
        $criteria->compare('id_organization', $id_organization);
        
        return $this->exists($criteria);
    }
	
    
    
    /**
     * Проверка доступа к объекту $model_name->$model_id для групп с ИД $groups_id
     * @param int $groups_id
     * @param string $model_name
     * @param int $model_id
     * @param string $id_organization
     * @return boolean
     *
     * @version 03.10.2017: create
     */    
    public function findAccessObjecGroups($groups_id, $model_name, $model_id, $id_organization=null)
    {
        if (Yii::app()->user->isGuest)
            return false;
        
        // если не указан код организации, то взять текущий код орагнизации из сессии
        if ($id_organization == null)
            $id_organization = Yii::app()->session['organization'];
                    
        // поиск
        $criteria = new CDbCriteria();
        $criteria->compare('access_mode', 'group');
        $criteria->addInCondition('access_identity', $groups_id);
        $criteria->compare('model_name', $model_name);
        $criteria->compare('model_id', $model_id);
        $criteria->compare('id_organization', $id_organization);
        
        return $this->exists($criteria);
    }
	    
	
    /**
     * Проверка доступа к объекту $model_name с ИД $model_id для текущего пользователя
     * (+ проверка с учетом вхождения пользователя в группы)
     * 
     * @param string $model_name
     * @param int $model_id
     * @param string $id_organization
     * @return boolean
     * 
     * @version 03.10.2017: create
     */
    public function findAccessObjectCurrentUserGroup($model_name, $model_id, $id_organization=null)
    {
        // получение списка         
        return $this->findAccessObjectUsers(Yii::app()->user->id, $model_name, $model_id, $id_organization)
            || ($this->findAccessObjecGroups(Yii::app()->user->userGroupsId, $model_name, $model_id));
    }
    
    
    
   
    
    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    ////// @todo DELETE!
	    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		/** 
		 * Сохранение прав доступа (группы и пользователи)
		 * привязанных к текущему УН структуры
		 * 
		 * @author oleg
		 * @version 12.08.2016
		 * 
		 **/
		public static function saveRelationsPermissions($tree_id, $model, $groups, $users)
		{
						
			$command = Yii::app()->db->createCommand();
			
			$command->delete('{{access_user}}', 'id_tree=:id and id_organization=:id_organization', 
					array(':id'=>$tree_id, ':id_organization'=>Yii::app()->session['organization']));
			$command->delete('{{access_group}}', 'id_tree=:id and id_organization=:id_organization', 
					array(':id'=>$tree_id, ':id_organization'=>Yii::app()->session['organization']));
		
		
			// если установлена галочка "Добавить разрешения, наследуемые от родительских групп и пользователей"
			if ($model->useParentRight)
			{
				if ($model->id_parent==0) return; // если нет родителя, то выходим из функции
		
				// сохраняем пользователей
				$command->reset();							
				$command->text = "INSERT INTO {{access_user}} (id_user,id_tree,id_organization,date_create)
							SELECT id_user,$tree_id,id_organization,getdate() FROM {{access_user}}
								WHERE id_tree=$model->id_parent";
				$command->execute();
		
				// сохраняем группы
				$command->reset();
				$command->text = "INSERT INTO {{access_group}} (id_group,id_tree,id_organization,date_create)
							SELECT id_group,$tree_id,id_organization,getdate() FROM {{access_group}}
								WHERE id_tree=$model->id_parent";
				$command->execute();
		
			}
			else
			{
				// сохранияем группы
				if (count($groups))
				{
					foreach ($groups as $value)
					{
						if (is_numeric($tree_id) && is_numeric($value))
						{
							$command->reset();
							$command->insert('{{access_group}}', array(
								'id_group'=>$value,
								'id_tree'=>$tree_id,
								'id_organization' => Yii::app()->session['organization'],
								'date_create'=>new CDbExpression('getdate()'),
							));
						}
					}
				}
		
				// сохранияем пользователей
				if (count($users))
				{
					foreach ($users as $value)
					{
						if (is_numeric($tree_id) && is_numeric($value))
						{
							$command->reset();
							$command->insert('{{access_user}}', array(
								'id_user'=>$value,
								'id_tree'=>$tree_id,
								'id_organization' => Yii::app()->session['organization'],
								'date_create'=>new CDbExpression('getdate()'),
							));
						}
					}
				}
			}
		}
		
		
		
		/** 
		 * Сохранение прав доступа к отделам (группы и пользователи)
		 * привязанных к текущему УН отдела
		 * 
		 * @author oleg
		 * @version 16.08.2016
		 * 
		 **/
		public static function saveRelationsPermissionsDepartment($model, $groups, $users)
		{
						
			$id = $model->id;
			
			$command = Yii::app()->db->createCommand();
			
			$command->delete('{{access_department_user}}', 'id_department=:id', array(':id'=>$id));
			$command->delete('{{access_department_group}}', 'id_department=:id', array(':id'=>$id));
		
			// сохранияем группы
			if (count($groups))
			{
				foreach ($groups as $value)
				{
					if (is_numeric($id) && is_numeric($value))
					{
						$command->reset();
						$command->insert('{{access_department_group}}', array(
								'id_group'=>$value,
								'id_department'=>$id,
								'id_organization' => Yii::app()->session['organization'],
								'date_create'=>new CDbExpression('getdate()'),
						));
					}
				}
			}
		
			// сохранияем пользователей
			if (count($users))
			{
				foreach ($users as $value)
				{
					if (is_numeric($id) && is_numeric($value))
					{
						$command->reset();
						$command->insert('{{access_department_user}}', array(
								'id_user'=>$value,
								'id_department'=>$id,
								'id_organization' => Yii::app()->session['organization'],
								'date_create'=>new CDbExpression('getdate()'),
						));
					}
				}
			}
			
		}
		
		
	
		/** 
		 * Получение списка групп и пользователей в виде списка
		 * 	с разделением <br /> для view контроллера TreeController
		 *  (admin/tree/view/id/$id_tree)
		 *  
		 * @author oleg
		 * @version 12.08.2016
		 *     
		 */
		public static function getListGroupUser($id_tree)
		{
			$icon_group = '<img src="/images/group.png" /> ';
			$icon_user = '<img src="/images/user.png" /> ';
		
			$result_str = '';
		
			$group = AccessGroup::model()->with('group')->findAll(array(
					'condition'=>'t.id_tree=:id_tree and t.id_organization=:id_organization',
					'params'=>array(':id_tree'=>$id_tree, ':id_organization'=>Yii::app()->session['organization']),
					'order'=>'[group].[name]',
			));
		
			foreach ($group as $val)
			{
				$result_str .= $icon_group.$val->group->name.'<br />';
			}
			 
			$user = AccessUser::model()->with('user')->findAll(array(
				'condition'=>'t.id_tree=:id_tree and t.id_organization=:id_organization',
				'params'=>array(':id_tree'=>$id_tree, ':id_organization'=>Yii::app()->session['organization']),
				'order'=>'[user].[username_windows], [user].[username]',
			));
			foreach ($user as $val)
			{
				$result_str .= $icon_user . $val->user->concatened;
			}
		
			return $result_str;
		}
		
		
		
		
		/**
		 * Проверка прав пользователя для структуры $id_tree
		 * @param integer $id_tree
		 * 
		 * @author oleg
		 * @version 12.08.2016
		 * 
		 */
		public static function checkAccessUserForTree($id_tree)
		{		
		    if (!is_numeric($id_tree))
		        return  false;
		    
			return Yii::app()->db->createCommand()
				->from('{{view_access_tree}}')
				->where('id=:id and id_user=:id_user', array(
					':id' => $id_tree,
					':id_user' => Yii::app()->user->id,
				))
				->queryScalar();
		}
		
		
		/**
		 * Получение списка подключенных пользователей
		 * для отдела с id = $id
		 * 
		 * @param int $id
		 * @return CDbCommand
		 * 
		 * @author oleg
		 * @version 16.08.2016
		 */
		public static function accessDepartmentUserById($id)
		{
			return Yii::app()->db->createCommand()
				->select("u.id, 
					case when u.username_windows is not null then u.username_windows else u.username end + 
					case when p.name is not null then ' (' + p.name + ')' end concatened")
				->from('{{access_department_user}} t ')
				->join('{{user}} u', 't.id_user = u.id')
				->leftJoin('{{profile}} p', 'u.id = p.id')
				->where('id_department=:id_department', [':id_department'=>$id])
				->queryAll();
		}
		
		
		
		public static function accessDepartmentGroupById($id)
		{		
			return Yii::app()->db->createCommand()
				->select('g.id, g.name')
				->from('{{access_department_group}} t')
				->join('{{group}} g', 't.id_group=g.id')
				->where('t.id_department=:id_department', [':id_department'=>$id])
				->queryAll();
		}
		
		
		
		
		/**
		 * Сохранение прав доступа к модулям (группы и пользователи)
		 *
		 * @author oleg
		 * @version 03.03.2017
		 *
		 **/
		public static function saveRelationsPermissionModule($model, $groups, $users)
		{
			$id = $model->name;
				
			$command = Yii::app()->db->createCommand();
				
			$command->delete('{{access_module_user}}', 'module_name=:id', array(':id'=>$id));
			$command->delete('{{access_module_group}}', 'module_name=:id', array(':id'=>$id));
			
			
				
			// сохранияем группы
			if (count($groups))
			{
				foreach ($groups as $value)
				{					
					$command->reset();
					$command->insert('{{access_module_group}}', array(
						'id_group'=>$value,
						'module_name'=>$id,
						'date_create'=>new CDbExpression('getdate()'),
					));
				}
			}
			
			// сохранияем пользователей
			if (count($users))
			{
				foreach ($users as $value)
				{					
					$command->reset();
					$command->insert('{{access_module_user}}', array(
						'id_user'=>$value,
						'module_name'=>$id,								
						'date_create'=>new CDbExpression('getdate()'),
					));
				}
			}
		}
		
		
		
		
		
		
		
	}