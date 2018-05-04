<?php

/**
 * This is the model class for table "{{interview}}".
 *
 * The followings are the available columns in table '{{interview}}':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $date_start
 * @property string $date_end
 * @property integer $count_like
 * @property string $date_create
 * @property string $date_edit
 * @property string $author
 * @property string $log_change
 * @property string $alias
 *
 * The followings are the available model relations:
 * @property InterviewQuestion[] $interviewQuestions
 */
class Interview extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{interview}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, date_start, date_end, date_create, author, alias', 'required'),
			array('count_like', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>300),
			array('author', 'length', 'max'=>250),
			array('alias', 'length', 'max'=>50),
			array('description, date_edit, log_change', 'safe'),
			// The following rule is used by search().			
			array('id, title, description, date_start, date_end, count_like, date_create, date_edit, author, log_change, alias', 'safe', 'on'=>'search'),
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
			'interviewQuestions' => array(self::HAS_MANY, 'InterviewQuestion', 'id_interview'),
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
			'title' => 'Title',
			'description' => 'Description',
			'date_start' => 'Date Start',
			'date_end' => 'Date End',
			'count_like' => 'Count Like',
			'date_create' => 'Date Create',
			'date_edit' => 'Date Edit',
			'author' => 'Author',
			'log_change' => 'Log Change',
			'alias' => 'Alias',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_end',$this->date_end,true);
		$criteria->compare('count_like',$this->count_like);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('log_change',$this->log_change,true);
		$criteria->compare('alias',$this->alias,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Interview the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Срок голосования истек
	 * @return boolean
	 */
	public function getIsExpiried()
	{      
	    $d1 = new DateTime("now");
	    $d2 = new DateTime($this->date_end);
	    return ($d1 >= $d2);
	}
	
}
