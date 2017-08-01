<?php

/**
 * This is the model class for table "{{department}}".
 *
 * The followings are the available columns in table '{{department}}':
 * @property integer $id
 * @property integer $id_tree
 * @property string $id_organization
 * @property string $department_index
 * @property string $department_name
 * @property string $date_create
 * @property string $date_edit
 * @property string $author
 * @property string $log_change
 *
 * The followings are the available model relations:
 * @property Tree $idTree
 * @property Organization $idOrganization
 */
class Department extends CActiveRecord
{
	
	public $useOptionalAccess = false; // флаг отвечающий за дополнительные настройки прав
	
	public $permissionUser;
	public $permissionGroup;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{department}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_tree, id_organization, department_index, department_name', 'required'),
			array('id_tree, use_card', 'numerical', 'integerOnly'=>true),
			array('id_organization', 'length', 'max'=>5),
			array('department_index', 'length', 'max'=>2),
			array('department_name, author', 'length', 'max'=>250),
			array('date_edit, log_change, permissionUser, permissionGroup', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_tree, id_organization, department_index, department_name, date_create, 
				date_edit, author, log_change', 'safe', 'on'=>'search'),
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
			'tree' => array(self::BELONGS_TO, 'Tree', 'id_tree'),
			'organization' => array(self::BELONGS_TO, 'Organization', 'id_organization'),
			'card' => array(self::HAS_MANY, 'DepartmentCard', 'id_department'),
			//'accessUser' => array(self::HAS_MANY, 'AccessDepartmentUser', 'id_department'=>'id'),
			//'accessGroup' => array(self::HAS_MANY, 'AccessDepartmentGroup', 'id_department'=>'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'id_tree' => 'ИД структуры',
			'id_organization' => 'Код НО',
			'department_index' => 'Индекс отдела',
			'department_name' => 'Наименование отдела',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
			'author' => 'Автор',
			'permissionUser'    => 'Пользователи',
			'permissionGroup'   => 'Группы',
			'log_change' => 'Журнал изменений',
			'concatened' => 'Отдел',
			'use_card' => 'Показывать структуру отдела',
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
	public function search($forUser=false)
	{
		
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.id_tree',$this->id_tree);
		$criteria->compare('t.id_organization',Yii::app()->session['organization']);
		$criteria->compare('t.department_index',$this->department_index,true);
		$criteria->compare('t.department_name',$this->department_name,true);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.date_edit',$this->date_edit,true);
		$criteria->compare('t.author',$this->author,true);		
	
		if ($forUser && !Yii::app()->user->admin)
		{
			$criteria->join =  ' left join {{access_department_user}} access_user on t.id = access_user.id_department';
			$criteria->join .= ' left join {{access_department_group}} access_group on t.id = access_group.id_department';
			$criteria->join .= ' left join {{group_user}} group_user on group_user.id_group = access_group.id_group';
			
			$criteria2 = new CDbCriteria;
			$criteria2->addColumnCondition(array('access_user.id_user' => Yii::app()->user->id), 'AND', 'OR');
			$criteria2->addColumnCondition(array('group_user.id_user' => Yii::app()->user->id),' AND', 'OR');
			$criteria->mergeWith($criteria2);
		}
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Department the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
	/**
	 * Стандартное событие AR beforeSave()
	 * @author oleg
	 * @version 15.08.2016
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->date_create = new CDbExpression('getdate()');
		}	
		else
		{
			$this->date_edit = new CDbExpression('getdate()');
		}
		
		return parent::beforeSave();
	}
	
	
	/**
	 * Стандартное событие AR afterFind()
	 * @author oleg
	 * @version 16.08.2016
	 */
	public function afterFind()
	{
		$this->date_create = ConvertDate::find($this->date_create);
		
		return parent::afterFind();
	}
	
	
	public function getConcatened()
	{
		return $this->department_index . ' ' . $this->department_name;
	}
	
	
	
	public static function departmentForMenu()
	{
		$model = self::model()->findAll(['order'=>'department_index']);
		$resArray = array();
		foreach ($model as $m)
		{
			$resArray[] = array(
				'label' => $m->concatened,
				'url' => ['/department/view', 'id'=>$m->id],
			);
		}
		return $resArray;
	}
	
	
	/**
	 * Меню отдела 
	 * 
	 * @param int $id
	 * @return array
	 * 	
	 * @author tvog17
	 * @version 06.03.2017 - create
	 * 			31.07.2017 - update: add rating 
	 */
	public function getMenu($id=null)
	{
		$resultMenu = array();
		
		if ($id===null)
		{			
			$id = $this->id_tree;
		}
		
			
		$model = Tree::model()->findAll('id_parent=:id /*and use_material<>1*/', array(':id'=>$id));
		
		foreach ($model as $m)
		{
			/**
			// rating
			if ($m->module == 'ratingData')
			{
				$modelRating = Yii::app()->db->createCommand()					
					->from('{{rating_main}}')
					->where('id_tree=:id_tree', [':id_tree'=>$m->id])
					->queryAll();
				if (count($modelRating) > 1)
				{
					// ratingID
				}
				else 
				{
					$resultMenu[] = array(
						'name'=>$m->name, 
						'link'=>['department/view', 'id'=>$this->id, 'idTree'=>$m->id],
						'items'=>$this->getMenu($m->id),
					);
				}
				
				continue;
			}
			**/
			
			$resultMenu[] = array(
				'name'=>$m->name, 
				'link'=>['department/view', 'id'=>$this->id, 'idTree'=>$m->id],
				'items'=>$this->getMenu($m->id),
			);
		}
		
		return $resultMenu;
		
	}
	
}
