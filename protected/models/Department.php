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
 * @property bool $use_card
 * @property integer $general_page_type
 * @property integer $general_page_tree_id
 *
 * The followings are the available model relations:
 * @property Tree $idTree
 * @property Organization $idOrganization
 */
class Department extends CActiveRecord
{
    /**
     * Показывать только первую новость
     * @var integer
     */
    const GP_SHOW_FIRST_NEWS = 0;
    
    /**
     * Показывать новости из списка 
     * @var integer
     */
    const GP_SHOW_NEWS_FROM_LIST = 1;
    
    /**
     * Показывать структуру отдела
     * @var integer
     */
    const GP_SHOW_STRUCT = 2;
    
    /**
     * Вид главной страницы отдела
     * @var array
     * @uses getTypeGeneralPage()
     */
    private $_typeGeneralPage = array(
        self::GP_SHOW_FIRST_NEWS => 'Отображать первую новость',
        self::GP_SHOW_NEWS_FROM_LIST => 'Показать новость из списка',
        self::GP_SHOW_STRUCT => 'Показать структуру отдела',
    );
    
	/**
	 * Дополнительные настройки прав
	 * @var boolean
	 */
	public $useOptionalAccess = false; 
	
	/**
	 * @deprecated
	 * @var unknown
	 */
	public $permissionUser;
	
	/**
	 * @deprecated
	 * @var unknown
	 */
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
			array('id_tree, use_card, general_page_type, general_page_tree_id', 'numerical', 'integerOnly'=>true),
			array('id_organization', 'length', 'max'=>5),
			array('department_index', 'length', 'max'=>2),
			array('department_name, author', 'length', 'max'=>250),
			array('date_edit, log_change, permissionUser, permissionGroup', 'safe'),			
			array('id, id_tree, id_organization, department_index, department_name, date_create, 
				date_edit, author, log_change', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{		
		return array(
			'tree' => array(self::BELONGS_TO, 'Tree', 'id_tree'),
			'organization' => array(self::BELONGS_TO, 'Organization', 'id_organization'),
			'card' => array(self::HAS_MANY, 'DepartmentCard', 'id_department'),			
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
		    'general_page_type' => 'Страница по умолчанию',
		    'general_page_tree_id' => 'Выбрать новость',
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
	 * @see CDbCriteria
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
	 * @static
	 */
	public static function model($className=__CLASS__)
	{	     
		return parent::model($className);
	}
	
	/**
	 * @see CActiveRecord::beforeSave()
	 * {@inheritdoc}
	 */
	protected function beforeSave()
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
	 * @see CActiveRecord::afterFind()
	 * @see DateHelper
	 * {@inheritdoc}
	 */
	protected function afterFind()
	{
		$this->date_create = DateHelper::explodeDateTime($this->date_create);		
		return parent::afterFind();
	}
	
	/**
	 * Возвращается индекс и наименование отдела
	 * @return string
	 */
	public function getConcatened()
	{
		return $this->department_index . ' ' . $this->department_name;
	}
	
	/**
	 * @return array
	 * @deprecated
	 */
	public static function departmentForMenu()
	{	    
	    $resArray = array();
	    $model = Yii::app()->db->createCommand()
	       ->from('{{department}}')
	       ->order('department_index asc')
	       ->queryAll();
	    foreach ($model as $m)
	    {
	        $resArray[] = [
	            'label' => $m['department_index'] . ' ' . $m['department_name'],
	            'url' => ['/department/view', 'id'=>$m['id']],
	        ];
	    }
		return $resArray;
	}
		
	/**
	 * Меню отдела
	 * @param int $id
	 * @return array
	 * @author oleg
	 * @uses DepartmentController::loadMenu()
	 */
	public function getMenu($id=null)
	{
		$resultMenu = array();		
		
		if ($id===null)
		{			
			$id = $this->id_tree;
		}
		
		$model = Yii::app()->db->createCommand()
		  ->from('{{tree}}')
		  ->where('id_parent=:id_parent and date_delete is null', [':id_parent'=>$id])
		  ->select('id, name')
		  ->queryAll();
		
		foreach ($model as $m)
		{
			$resultMenu[] = array(
				'name'=>$m['name'], 
				'link'=>['department/view', 'id'=>$this->id, 'idTree'=>$m['id']],
				'items'=>$this->getMenu($m['id']),
			);
		}		
		return $resultMenu;		
	}
	
	/**
	 * Разновидность страницы для установки по умолчанию
	 * @return array
	 */
	public function getTypeGeneralPage()
	{
	    return $this->_typeGeneralPage;
	}
	
	/**
	 * Список новостей для dropDownList, в качестве установки стрницы по умолчанию
	 * @see CHtml
	 * @see News
	 * @return array
	 */
	public function getTreeList()
	{
	    return CHtml::listData(News::model()->findAll('id_tree=:id_tree and date_delete is null and flag_enable=1', 
	        [':id_tree'=>$this->id_tree]), 'id', 'title');	    
	}
	
	/**
	 * Спроверка прав доступа для пользователя к отделу
	 * с идентификатором $idDepartment
	 * @param int $idDepartment идентификатор отдела
	 * @return boolean
	 * @uses DepartmnetCardController::userDepartmentRight() (admin)
	 */
	public static function checkAccessUser($idDepartment)
	{
	    if (Yii::app()->user->admin) return true;
	    
	    return Yii::app()->db->createCommand()    	   
    	    ->from('{{department}} d')
    	    ->leftJoin('{{access_department_user}} access_user', 'd.id = access_user.id_department')
    	    ->leftJoin('{{access_department_group}} access_group', 'd.id = access_group.id_department')
    	    ->leftJoin('{{group_user}} group_user', 'access_group.id_group = group_user.id_group')
    	    ->where('group_user.id_user=:user1 or access_user.id_user=:user2 and d.id=:id_department',
    	        [
    	            ':user1'=>Yii::app()->user->id,
    	            ':user2'=>Yii::app()->user->id, 
    	            ':id_department'=>$idDepartment,    	            
    	        ])
	        ->queryRow();
	}
	
	
}
