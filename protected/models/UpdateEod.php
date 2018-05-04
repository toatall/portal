<?php

/**
 * This is the model class for table "{{update_eod}}".
 *
 * The followings are the available columns in table '{{update_eod}}':
 * @property integer $id
 * @property string $support
 * @property string $name
 * @property string $path
 * @property string $date_update
 * @property string $date_create
 * @property string $log_change
 * 
 * 
 * @deprecated
 */
class UpdateEod extends CActiveRecord
{
    
    public $useOptionalAccess = false; // флаг отвечающий за дополнительные настройки прав
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{update_eod}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('support, name', 'required'),
			array('support, name', 'length', 'max'=>250),
			array('path', 'length', 'max'=>500),
			array('log_change', 'length', 'max'=>5000),
			array('date_update, date_create', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, support, name, path, date_update, date_create, log_change', 'safe', 'on'=>'search'),
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
			'id' => 'УН',
			'support' => 'Сопровождение',
			'name' => 'Название',
			'path' => 'Место расположения на FTP-сервере',
			'date_update' => 'Дата обновления',
			'date_create' => 'Дата создания',
			'log_change' => 'История изменений',
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
		$criteria->compare('support',$this->support,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('log_change',$this->log_change,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UpdateEod the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
    protected function beforeSave()
    {
        if ($this->isNewRecord)
            $this->date_create = new CDbExpression('NOW()');
        $this->log_change = LogChange::setLog($this->log_change,
            ($this->isNewRecord ? 'создание' : 'изменение'));
        $this->date_update = ($this->date_update=='') ? new CDbExpression('NULL')
            : new CDbExpression("STR_TO_DATE('".$this->date_update."','%d.%m.%Y')");
        return parent::beforeSave();
    }
    
    protected function afterFind()
    {        
        $this->date_update = date('d.m.Y', strtotime($this->date_update));
        parent::afterFind();
    }
    
}
