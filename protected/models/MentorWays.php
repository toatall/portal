<?php

/**
 * This is the model class for table "{{mentor_ways}}".
 *
 * The followings are the available columns in table '{{mentor_ways}}':
 * @property integer $id
 * @property string $name
 * @property string $date_create
 * @property string $date_update
 */
class MentorWays extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mentor_ways}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, date_create', 'required'),
			array('name', 'length', 'max'=>200),
			array('date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, date_create, date_update', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'name' => 'Name',
			'date_create' => 'Date Create',
			'date_update' => 'Date Update',
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
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_update',$this->date_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MentorWays the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Количество постов
	 * @return integer
	 */
	public function getCountPosts()
	{
	    return Yii::app()->db->createCommand()
	       ->select('count(*)')
	       ->from('{{mentor_post}}')
	       ->where('date_delete is null and id_mentor_ways=:id_mentor_ways', [':id_mentor_ways'=>$this->id])
	       ->queryScalar();
	}
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 */
	protected function afterFind()
	{
	    $this->date_create = DateHelper::explodeDateTime($this->date_create);
	    $this->date_update = DateHelper::explodeDateTime($this->date_update);
	}
	
}
