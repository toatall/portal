<?php

/**
 * This is the model class for table "{{comment}}".
 *
 * The followings are the available columns in table '{{comment}}':
 * @property integer $id
 * @property integer $id_parent
 * @property string $comment
 * @property string $date_create
 * @property string $date_delete
 * @property string $username
 * @property string $ip_address
 * @property string $hostname
 *
 * The followings are the available model relations:
 * @property News $idParent
 */
class Comment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_parent, comment', 'required'),
			array('id_parent', 'numerical', 'integerOnly'=>true),
			array('username, ip_address, hostname, date_create, date_delete', 'unsafe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_parent, comment, date_create, date_delete, username, ip_address, hostname', 'safe', 'on'=>'search'),
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
			'idParent' => array(self::BELONGS_TO, 'News', 'id_parent'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'id_parent' => 'ИД новости',
			'comment' => 'Комментарий',
			'date_create' => 'Дата создания',
			'date_delete' => 'Дата удаления',
			'username' => 'Имя пользователя',
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
		$criteria->compare('id_parent',$this->id_parent);
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
	 * @return Comment the static model class
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
		$this->date_create = new CDbExpression('getdate()');
		return parent::beforeSave();
	}
	
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterSave()
	 */
	protected function afterSave()
	{
		if ($this->isNewRecord)
		{
			Yii::app()->db->createCommand('exec p_pr_comment_news @id_news=:id')
			->bindValue(':id', $this->id_parent)
			->execute();
		}
	}
	
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 */
	protected function afterFind()
	{
		$this->date_create = DateHelper::explodeDateTime($this->date_create);
	}
	
	
	
	/**
	 * Кнопка удаления комментария
	 * @return string
	 */
	public function getButtonDelete()
	{
		if ((Yii::app()->user->inRole(['admin']) || $this->username == UserInfo::inst()->userLogin))
		{
			$url = Yii::app()->controller->createUrl('comment/delete',['id'=>$this->id]);
			return '<button onclick="deleteComment(' . $this->id . ', \'' . $url . '\');" class="btn btn-danger">Удалить</button>';
		}
	}
	
	
	
	
	
}
