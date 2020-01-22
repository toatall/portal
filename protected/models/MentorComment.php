<?php

/**
 * This is the model class for table "{{mentor_comment}}".
 *
 * The followings are the available columns in table '{{mentor_comment}}':
 * @property integer $id
 * @property integer $id_mentor_post
 * @property string $comment
 * @property string $date_create
 * @property string $date_delete
 * @property string $username
 * @property string $ip_address
 * @property string $hostname
 */
class MentorComment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mentor_comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_mentor_post, comment, username', 'required'),
			array('id_mentor_post', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>250),
			array('ip_address, hostname', 'length', 'max'=>50),
			array('date_create, date_delete', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_mentor_post, comment, date_create, date_delete, username, ip_address, hostname', 'safe', 'on'=>'search'),
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
			'id_mentor_post' => 'Id Mentor Post',
			'comment' => 'Комментарий',
			'date_create' => 'Date Create',
			'date_delete' => 'Date Delete',
			'username' => 'Username',
			'ip_address' => 'Ip Address',
			'hostname' => 'Hostname',
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
		$criteria->compare('id_mentor_post',$this->id_mentor_post);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_delete',$this->date_delete,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('hostname',$this->hostname,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MentorComment the static model class
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
	    if (!parent::beforeSave())
	    {
	        return false;
	    }
	    if ($this->isNewRecord)
	    {
	        $this->date_create = new CDbExpression('getdate()');
	    }
	    return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterSave()
	 */
	protected function afterSave()
	{
	    if ($this->isNewRecord || $this->date_delete != null)
	    {
	        Yii::app()->db->createCommand("update p_mentor_post
	              set count_comment = (select count(*) from p_mentor_comment where id_mentor_post={$this->id_mentor_post} and date_delete is null)
               where id={$this->id_mentor_post}")	           
           ->execute();
	    }
	}
	
	/**
	 * Событие после поиска записи
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 * @see DateHelper
	 */
	protected function afterFind()
	{
	    $this->date_create = DateHelper::explodeDateTime($this->date_create);
	}
}
