<?php

/**
 * This is the model class for table "{{rating_file}}".
 *
 * The followings are the available columns in table '{{rating_file}}':
 * @property integer $id
 * @property integer $id_rating_data
 * @property string $file_path
 * @property string $date_create
 * @property string $author
 * @property integer $count_download
 *
 * The followings are the available model relations:
 * @property RatingData $idRatingData
 */
class RatingFile extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{rating_file}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_rating_data, file_path', 'required'),
			array('id_rating_data, count_download', 'numerical', 'integerOnly'=>true),
			array('file_path', 'length', 'max'=>500),
			array('author', 'length', 'max'=>250),
			array('date_create', 'safe'),
			// The following rule is used by search().			
			array('id, id_rating_data, file_path, date_create, author, count_download', 'safe', 'on'=>'search'),
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
			'idRatingData' => array(self::BELONGS_TO, 'RatingData', 'id_rating_data'),
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
			'id_rating_data' => 'Id Rating Data',
			'file_path' => 'File Path',
			'date_create' => 'Date Create',
			'author' => 'Author',
			'count_download' => 'Count Download',
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
		$criteria->compare('id_rating_data',$this->id_rating_data);
		$criteria->compare('file_path',$this->file_path,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('count_download',$this->count_download);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RatingFile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
