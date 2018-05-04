<?php

/**
 * This is the model class for table "{{reestr_svt_message}}".
 *
 * The followings are the available columns in table '{{reestr_svt_message}}':
 * @property integer $id
 * @property integer $id_reestr
 * @property string $msg
 * @property string $date_create
 * @property string $author
 * @property string $status_msg
 *
 * The followings are the available model relations:
 * @property ReestrSvt $idReestr
 * @deprecated
 */
class ReestrSvtMessage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{reestr_svt_message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_reestr, msg, date_create, author', 'required'),
			array('id_reestr', 'numerical', 'integerOnly'=>true),
			array('author', 'length', 'max'=>200),
			array('status_msg', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_reestr, msg, date_create, author, status_msg', 'safe', 'on'=>'search'),
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
			'idReestr' => array(self::BELONGS_TO, 'ReestrSvt', 'id_reestr'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_reestr' => 'Id Reestr',
			'msg' => 'Msg',
			'date_create' => 'Date Create',
			'author' => 'Author',
			'status_msg' => 'Status Msg',
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
		$criteria->compare('id_reestr',$this->id_reestr);
		$criteria->compare('msg',$this->msg,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('status_msg',$this->status_msg,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReestrSvtMessage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
