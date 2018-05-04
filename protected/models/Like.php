<?php

/**
 * This is the model class for table "{{like}}".
 *
 * The followings are the available columns in table '{{like}}':
 * @property integer $id
 * @property integer $id_parent
 * @property string $username
 * @property string $ip_address
 * @property string $date_create
 *
 * The followings are the available model relations:
 * @property News $idNews
 */
class Like extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{like}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_parent, username', 'required'),
			array('id_parent', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>250),
			array('ip_address', 'length', 'max'=>50),
			array('date_create', 'safe'),
			// The following rule is used by search().			
			array('id, id_parent, username, ip_address, date_create', 'safe', 'on'=>'search'),
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
			'idNews' => array(self::BELONGS_TO, 'News', 'id_parent'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 * @deprecated
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_parent' => 'Id News',
			'username' => 'Username',
			'ip_address' => 'Ip Address',
			'date_create' => 'Date Create',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('date_create',$this->date_create,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Like the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
