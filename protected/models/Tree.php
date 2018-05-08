<?php

/**
 * This is the model class for table "{{Tree}}".
 *
 * The followings are the available columns in table '{{Tree}}':
 * @property integer $id
 * @property integer $id_parent
 * @property string $name
 * @property integer $sort
 * @property string $module
 * @property boolean $use_organization
 * @property boolean $use_tape
 * @property string $date_create
 * @property string $date_modification
 * @property bool $disable_child
 * @property bool $allowAccess
 * @property string $alias
 *
 * The followings are the available model relations:
 * @property TreeOrganization[] $TreeOrganizations
 */
class Tree extends CActiveRecord
{    
    /**
     * Модуль по умолчанию
     * @var string
     */
    const defaultModule = 'news';
    
    /**
     * Доступ пользователей к узлу
     * @var array
     */
    public $permissionUser;
    
    /**
     * Доступ групп к узлу
     * @var array
     */
    public $permissionGroup;
    
    /**
     * Галочка, которая отвечает за наследование прав
     * @var bool
     */
    public $useParentRight;
    
    /**
     * Предназначен ли данный узел для всех орагнизаций
     * @var bool
     */
    public $allOrganization;
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{Tree}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_parent, name', 'required'),
			array('id_parent, sort, id_organization', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>250),
			array('module, author, alias', 'length', 'max'=>50),
			array('param1', 'length', 'max'=>100),
			array('allOrganization, disable_child', 'boolean'),
            array('module', 'checkModule'),
			array('use_organization, use_tape, use_material,  
                permissionUser, permissionGroup, useParentRight', 'safe'),
			// The following rule is used by search().			
			array('id, id_parent, id_organization, name, sort, module, use_organization, 
                use_tape, use_material, date_create, log_change, organization, disable_child, 
                alias', 'safe', 'on'=>'search'),
		);
	}
    
	/**
	 * ПРАВИЛО. Проверка использования модуля более 1 раза
	 * Только для модулей, который необходимо использовать 1 раз
	 * @param string $attribute
	 * @see Tree
	 * @see Module
	 */
    public function checkModule($attribute)
    {   
        $moduleExists = Module::model()->exists('name=:name AND only_one=1', array(':name'=>$this->module));
        $treeExists = Tree::model()->exists('id<>:id AND module=:module', array(':id'=>(!$this->isNewRecord ? $this->id : 0), ':module'=>$this->module));
        if ($moduleExists && $treeExists)
        {
            $treeName = Tree::model()->find('module=:module', array(':module'=>$this->module));
            $this->addError($attribute, 'Модуль уже используется в разделе &laquo;'.$treeName->name.'&raquo;');
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//'TreeOrganizations' => array(self::HAS_MANY, 'TreeOrganization', 'id_Tree'),
            'modules' => array(self::HAS_MANY, 'Module', 'name'),
            //'permissionUsers' => array(self::HAS_MANY, 'Access', 'id_tree'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                => 'ИД',
			'id_parent'         => 'ИД родителя',
            'id_organization'   => 'Раздел',
			'name'              => 'Наименование',
			'sort'              => 'Сортровка',
			'module'            => 'Модуль',
			'use_organization'  => 'Группировка по справочнику организации',
			'use_tape'          => 'Использовать ленту',
            'use_material'      => 'Размещать метериалы в этом разделе',
			'date_create'       => 'Дата создания',
			'date_modification' => 'Дата изменения',
            'organization'      => 'Налоговый орган',
            'permissionUser'    => 'Пользователи',
            'permissionGroup'   => 'Группы',
            'useParentRight'    => 'Добавить разрешения, наследуемые от родительских групп и пользователей',
            'author'            => 'Автор',
            'log_change'        => 'Журнал изменений',
			'allOrganization'   => 'Для всех налоговых орнанов',	
			'param1'			=> 'ИД ссылки (для модуля page)', 
			'disable_child'		=> 'Запретить создание подразделов',
		    'alias'             => 'Алиас',
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
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_parent',$this->id_parent);
        $criteria->compare('id_organization',$this->id_organization);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('module',$this->module,true);
		$criteria->compare('use_organization',$this->use_organization);
		$criteria->compare('use_tape',$this->use_tape);
        $criteria->compare('use_material',$this->use_material);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_modification',$this->date_modification,true);
		$criteria->addInCondition('organization', array('0000', Yii::app()->session['organization']));        
        if (!Yii::app()->user->inRole(['admin']))
            $criteria->compare('date_delete', CDbExpression('NULL'));
       	$criteria->compare('disable_child', $this->disable_child);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tree the static model class
	 */
	public static function model($className=__CLASS__)
	{	   
	    return parent::model($className);
	}
    
	/**
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
        $this->log_change = Log::setLog($this->log_change, ($this->isNewRecord ? 'создание' : 'изменение'));
        
        $this->id_organization = Yii::app()->session['organization'];
        
        if ($this->allOrganization)
        	$this->id_organization = '0000';
        
        return parent::beforeSave();        
    }
    
    /**
     * {@inheritDoc}
     * @see CActiveRecord::afterFind()
     */
    protected function afterFind()
    {       
    	$this->date_create = DateHelper::explodeDateTime($this->date_create);
    	$this->allOrganization = ($this->id_organization == '0000');
        parent::afterFind();
    }
        
    /**
     * Дерево структуры для DropDownList
     * @param int $id идентификатор структуры
     * @param int $parent_id идентификатор родителя
     * @param number $level уровень
     * @see Tree
     * @return array|number
     */
    public function getTreeDropDownList($id=0, $parent_id=0, $level=1)
    {        
        $criteria=new CDbCriteria;
        $criteria->addCondition('id_parent='.$parent_id);
        $criteria->addCondition('id<>'.$id);
        $criteria->addInCondition('id_organization', array('0000', Yii::app()->session['organization']));
        $criteria->compare('disable_child', 0);
        if (!Yii::app()->user->inRole(['admin']))
            $criteria->addCondition('date_delete is null');    
        $criteria->order = 'sort asc, name asc, date_create asc';
        
        $data = array();
        $orgData = Tree::model()->findAll($criteria);
        foreach ($orgData as $value)
        {     
            if (Yii::app()->user->inRole(['admin']) || Access::checkAccessUserForTree($value->id))
            {                
                $item = array($value->id => str_repeat('--', $level).' '.$value->name);
                $flagLevel = 1;
            }
            else
            {
                $item = array();
                $flagLevel = 0;
            }
            $data = $data + $item + $this->getTreeDropDownList($id, $value->id, $level+$flagLevel);
        }
        return $data;
    }
    
    /**
     * Наименование структуры
     * @param int $id идентификатор структуры
     * @return string
     */
    public function getNameById($id)
    {
        $data = Tree::model()->findByPk($id);
        if ($data !== null)
            return $data->name;
        return 'Родитель';
    }
        
    /**
     * Построение дерева структуры сайта
     * относительно текущего НО (Yii::app()->session['code_no'])
     * @param int $id идентификатор структуры
     * @param int $parent_id идентификатор родителя
     * @return array
     * @uses TreeController::actionAdmin() (admin)
     */
    public function getTree($id=0, $parent_id=0)
    {
        $criteria=new CDbCriteria;
        $criteria->addCondition('id_parent='.$parent_id);
        $criteria->addCondition('id<>'.$id);        
        $criteria->addInCondition('id_organization', array('0000', Yii::app()->session['organization']));
        if (!Yii::app()->user->inRole(['admin']))
            $criteria->addCondition('date_delete is null'); 
       	$criteria->order = 'sort asc, name asc, date_create asc';
                
        $orgData = Tree::model()->findAll($criteria);
        
        $data = array();
        
        foreach ($orgData as $value)
        {   
            if (Yii::app()->user->inRole(['admin']) || Access::checkAccessUserForTree($value->id))
            {
                $data[] = array(
                    'id'=>$value->id,
                    'text'=>'<i class="icon-folder-open"></i>&nbsp;'
                        .($value->date_delete!='' ? '<span style="color:red; text-decoration:line-through;">' : '')
                        .$value->name.($value->date_delete!='' ?'</span>':'').'&nbsp'
                        .($this->checkParentRight($value->id_parent) ?
                             CHtml::link('<i class="icon-eye-open"></i>', 
                                array('view', 'id'=>$value->id),
                                array('class'=>'view', 'data-original-title'=>'Просмотреть', 'rel'=>'tooltip')).'&nbsp'
                            .CHtml::link('<i class="icon-pencil"></i>', 
                                array('update', 'id'=>$value->id),
                                array('class'=>'update', 'data-original-title'=>'Редактировать', 'rel'=>'tooltip')).'&nbsp'
                            .CHtml::link('<i class="icon-trash"></i>', 
                                '#', 
                                array(
                                    'submit'=>array('delete', 'id'=>$value->id),
                                    'confirm'=>'Вы уверены что хотите удалить "'.$value->name.'"? Все дочерние подразделы будут удалены!',
                                    'class'=>'delete',
                                    'data-original-title'=>'Удалить',
                                    'rel'=>'tooltip',
                                )
                        ) : ''),
                    'children'=>$this->getTree($id, $value->id),
                );
            }
            else
            {
                $data = array_merge($data, $this->getTree($id, $value->id));
            }
        }
        return $data;
    }
    
    /**
     * Построение дерева структуры сайта на главной странице административной зоны
     * относительно текущего НО (Yii::app()->session['organization'])
     * @param number $id
     * @param number $parent_id
     * @return array
     */
    public function getTreeForMain($id=0, $parent_id=0)
    {        
        $criteria=new CDbCriteria;
        $criteria->addCondition('id_parent='.$parent_id);
        $criteria->addCondition('id<>'.$id);
        $criteria->addInCondition('id_organization', array(Yii::app()->session['organization'],'0000'));                 
        if (!Yii::app()->user->inRole(['admin']))
            $criteria->addCondition('date_delete is null');  
        $criteria->order = 'sort asc, name asc, date_create asc';
                
        $orgData = Tree::model()->findAll($criteria);
        
        $data = array();
        foreach ($orgData as $value)
        {   
            if (Yii::app()->user->inRole(['admin']) || Access::checkAccessUserForTree($value->id))
            {                                
                $data[] = array(
                    'id'=>$value->id,
                    'text'=>'<div style="margin-top:-4px; margin-left:4px;"><i class="icon-folder-open"></i>&nbsp;'
                        .($value->module!='' ? 
                            CHtml::link($value->name, array($value->module.'/admin', 'idTree'=>$value->id))
                            	: $value->name).'</div>',
                    'children'=>$this->getTreeForMain($id, $value->id),
                    'htmlOptions'=>array('style'=>'font-weight:bold;'),
                );
            }
            else
            {
                $data = array_merge($data, $this->getTreeForMain($id, $value->id));
            }
        }
        return $data;
    }
        
    /**
     * Проверка прав у родительских разделов для текущего пользователя
     * относительно раздела $idParent
     * @param int $idParent идентификатор родителя
     * @return boolean
     * @uses NewsController::loadModel() (admin)
     * @uses PageController::loadModel() (admin)
     * @uses PageController::loadModelTree() (admin)
     * @uses TelephoneController::loadModel() (admin)
     * @uses TreeController::loadModel() (admin)
     */
    public function checkParentRight($idParent)
    {
        if (Yii::app()->user->inRole(['admin'])) return true;
        
        $record = Yii::app()->db->createCommand()
        	->from('{{tree}} a')
        	->leftJoin('{{view_access_tree}} b', 'a.id=b.id')
        	->where('a.id=:id_tree and b.id_user=:id_user', array(
        		':id_tree' => $idParent,
        		':id_user' => Yii::app()->user->id,
        	))        	
        	->queryAll();

        if (count($record))
        {
            return true;
        }
        else
        {
            if (count($record))
            {
                return $this->checkParentRight($record[0]['id_parent']);
            }
            else
            {
                return false;
            }
        }
    }
    
	/**
	 * Check correct using tree organization
	 * @param int $id
	 * @return boolean
	 * @uses RatingDataController::actionViewRating()
	 */
	public static function checkTreeNode($id)
	{
		$model = self::model()->findByPk($id);
		// Step 1: check exists model Tree
		if ($model===null)
			return false;
		// Step 2: if model Module not use one organization and Tree.id_organization not equal current (in session) organization then false
		$modelModule = Module::model()->findByPk($model->module);
		if ($modelModule===null)
			return false;
		if (!$modelModule->only_one && $model->id_organization<>Yii::app()->session['organization'])
			return false;		
		return true;
	}
	
	/**
	 * Проверка прав для аутентефицированного пользователя к текущему узлу
	 * @return bool
	 */
	public function getAllowAccess()
	{
	    return Yii::app()->user->inRole(['admin']) || 
	        Yii::app()->db->createCommand()
        	    ->from('{{view_access_tree}}')
        	    ->where('id=:id and id_user=:id_user and id_organization=:organization', array(
        	        ':id' => $this->id,
        	        ':id_user' => Yii::app()->user->id,
        	        ':organization' => Yii::app()->session['organization'],
        	    ))
        	    ->queryScalar();	    
	}
	
	/**
	 * Журнал изменений (адаптированый для просмотра)
	 * @return string
	 */
	public function getLogChangeText()
	{
	    return Log::getLog($this->log_change);
	}
	
}
