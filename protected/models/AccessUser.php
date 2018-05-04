<?php

/**
 * This is the model class for table "{{access_user}}".
 *
 * The followings are the available columns in table '{{access_user}}':
 * @property integer $id
 * @property integer $id_tree
 * @property integer $id_user
 * @property string $id_organization
 * @property string $date_create
 *
 * The followings are the available model relations:
 * @property Tree $idTree
 * @property User $idUser
 * @property AccessTelephoneUser[] $accessTelephoneUsers
 * @deprecated
 */
class AccessUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{access_user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_tree, id_user, id_organization, date_create', 'required'),
			array('id_tree, id_user', 'numerical', 'integerOnly'=>true),
			array('id_organization', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_tree, id_user, id_organization, date_create', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'id_user'),
			'accessTelephoneUsers' => array(self::HAS_MANY, 'AccessTelephoneUser', 'id_access_user'),
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
			'id_user' => 'ИД пользвателя',
			'id_organization' => 'ИД организации',
			'date_create' => 'Дата создания',
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
		$criteria->compare('id_tree',$this->id_tree);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_organization',$this->id_organization,true);
		$criteria->compare('date_create',$this->date_create,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccessUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
	
}
