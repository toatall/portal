<?php

/**
 * This is the model class for table "{{group}}".
 *
 * The followings are the available columns in table '{{group}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $no
 * @property string $date_create
 * @property string $date_modification
 *
 * The followings are the available model relations:
 * @property GroupUser[] $groupUsers
 */
class Group extends CActiveRecord
{    
	
	public $groups;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{group}}';
	}
        
    
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'unique', 'attributeName'=>'name', 'className'=>'Group'),
			array('name', 'required'),
			array('id, sort', 'numerical', 'integerOnly'=>true),
			array('id_organization', 'length', 'max'=>5, 'min'=>4),
			array('name', 'length', 'max'=>250),
			array('description', 'length', 'max'=>500),
			array('groups', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, date_create, date_edit, sort, id_organization', 'safe', 'on'=>'search'),
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
			'groupUsers' => array(self::MANY_MANY, 'User', '{{group_user}} (id_group, id_user)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'УН',
			'name' => 'Наименование',
			'description' => 'Описание',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
            'sort' => 'Сортировка',
            'id_organization' => 'Организация',
            'groupUsers' => 'Участники группы',            
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
        $criteria->compare('sort',$this->sort,true);
        //$criteria->compare('id_organization',$this->id_organization,true);
        $criteria->compare('id_organization', isset(Yii::app()->session['organization']) 
        	? Yii::app()->session['organization'] : $this->id_organization);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}     
    
    public function searchForTree()
    {
        //$groups = Yii::app()->request->getParam('groups');
        
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);		
        $criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);	
        if (Yii::app()->user->admin)
        {
            $criteria->compare('id_organization',$this->id_organization);
        }
        else
        {
            $criteria->compare('organization',Yii::app()->session['organization']);   
        }        
        $criteria->addNotInCondition('id',explode(',', $this->groups));
        
        return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id_organization asc, name asc',
            ),
		));
    } 
      
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Group the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
    protected function afterFind()
    {               
        $this->date_create = DateHelper::explodeDateTime($this->date_create);
        $this->date_edit = DateHelper::explodeDateTime($this->date_edit);
        
        parent::afterFind();
    }
    
    
    protected function beforeSave()
    {        
        if ($this->isNewRecord)                   
        {
        	$this->date_create = new CDbExpression('getdate()');
        	$this->id_organization = Yii::app()->session['organization'];
        }
        else
        {
        	$this->date_edit = new CDbExpression('getdate()');
        }               
        
        return parent::beforeSave();
    }      
    
    
    protected function afterSave()
    {
        $command = Yii::app()->db->createCommand();
        $command->delete('{{group_user}}', 'id_group=:id', array(':id'=>$this->id));
        foreach ($this->groupUsers as $val)
        {
            if (!is_numeric($val)) continue;
            $command->Reset();
            $command->insert('{{group_user}}', array(
                'id_user'=>$val,
                'id_group'=>$this->id,
                'date_create'=>new CDbExpression('getdate()'),
            ));
        }                
        Log::insertLog($this);        
        return parent::afterSave();
    }
    
    
    
    /**
     * 
     * @return NULL[]
     */
    public function getListGroupUsers()
    {
        $result_array = array();
        foreach ($this->groupUsers as $val)
        {
            $result_array[$val->id] = $val->concatened;
        }
        return $result_array;
    }
            
}
