<?php

/**
 * This is the model class for table "{{ifns}}".
 *
 * The followings are the available columns in table '{{ifns}}':
 * @property string $code
 * @property string $name
 * @property string $date_create
 * @property string $date_modification
 * @property boolean $enabled
 * @property string $sort
 */
class Ifns extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ifns}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, name', 'required'),
            array('sort', 'numerical', 'integerOnly'=>true),
            array('code', 'unique', 'attributeName'=>'code', 'className'=>'Ifns'),
			array('code', 'length', 'max'=>4),
			array('name', 'length', 'max'=>250),			
			array('code, name, date_create, date_modification, enabled, sort', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 * @deprecated
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
			'code' => 'Код НО',
			'name' => 'Наименование',
			'date_create' => 'Дата создания',
			'date_modification' => 'Дата изменения',
			'enabled' => 'Действующий НО',
            'sort' => 'Сортировка',
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

		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_modification',$this->date_modification,true);
		$criteria->compare('enabled',$this->enabled);
        $criteria->compare('sort', $this->sort, true);
        
        $criteria->order = 'sort ASC, code ASC';
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ifns the static model class
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
        }
        else 
        {
            $this->date_modification = new CDbExpression('getdate()');
        }
        return parent::beforeSave();
    }
    
    /**
     * {@inheritDoc}
     * @see CActiveRecord::afterFind()
     */
    protected function afterFind()
    {
        $this->date_create = DateHelper::explodeDateTime($this->date_create);
        $this->date_modification = DateHelper::explodeDateTime($this->date_modification);
        parent::afterFind();
    }
    
    
    
}
