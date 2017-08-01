<?php

/**
 * This is the model class for table "{{sez_corr}}".
 *
 * The followings are the available columns in table '{{sez_corr}}':
 * @property integer $id
 * @property integer $id_parent
 * @property integer $id_sez
 * @property string $message
 * @property integer $id_author
 * @property string $date_create
 *
 * The followings are the available model relations:
 * @property Sez $idSez
 * @property User $idAuthor
 * @property SezCorrRecip[] $sezCorrRecips
 */
class SezCorr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sez_corr}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message', 'required'),
			array('id_parent, id_sez, id_author', 'numerical', 'integerOnly'=>true),
			array('id, id_parent, id_sez, id_author, date_create', 'unsafe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_parent, id_sez, message, id_author, date_create', 'safe', 'on'=>'search'),
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
			'idSez' => array(self::BELONGS_TO, 'Sez', 'id_sez'),
			'idAuthor' => array(self::BELONGS_TO, 'User', 'id_author'),
			'sezCorrRecips' => array(self::HAS_MANY, 'SezCorrRecip', 'id_sez_corr'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'id_parent' => 'ИД родителя',
			'id_sez' => 'ИД заявки',
			'message' => 'Сообщение',
			'id_author' => 'Автор',
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
		$criteria->compare('id_parent',$this->id_parent);
		$criteria->compare('id_sez',$this->id_sez);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('id_author',$this->id_author);
		$criteria->compare('date_create',$this->date_create,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SezCorr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
