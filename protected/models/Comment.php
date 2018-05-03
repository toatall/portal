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
	 * Наименование таблицы
	 * {@inheritdoc}
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{comment}}';
	}

	/**
	 * Правила проверки вводимых данных
	 * {@inheritdoc}
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{		
		return array(
			array('id_parent, comment', 'required'),
			array('id_parent', 'numerical', 'integerOnly'=>true),
			array('username, ip_address, hostname, date_create, date_delete', 'unsafe'),			
			array('id, id_parent, comment, date_create, date_delete, username, ip_address, hostname', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Связи с другими классами (таблицами)
	 * {@inheritdoc}
	 * @return array relational rules.
	 */
	public function relations()
	{		
		return array(
			'idParent' => array(self::BELONGS_TO, 'News', 'id_parent'),
		);
	}

	/**
	 * Наименование аттрибутов
	 * {@inheritdoc}
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
	 * {@inheritdoc}
	 */
	public function search()
	{		
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
	 * {@inheritdoc}
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * Событие перед сохранением записи
	 * {@inheritDoc}
	 * @see CActiveRecord::beforeSave()
	 */
	protected function beforeSave()
	{		
		$this->date_create = new CDbExpression('getdate()');
		return parent::beforeSave();
	}
		
	/**
	 * Событие после сохранения записи
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
	 * Событие после поиска записи
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 * @see DateHelper
	 */
	protected function afterFind()
	{
		$this->date_create = DateHelper::explodeDateTime($this->date_create);
	}
	
	
	/**
	 * @todo Where use?
	 * Кнопка удаления комментария
	 * @return string
	 */
	public function getButtonDelete()
	{
		if ((Yii::app()->user->inRole(['admin']) || $this->username == UserInfo::inst()->userLogin))
		{
			$url = Yii::app()->controller->createUrl('comment/delete',['id'=>$this->id]);
			return '<button onclick="deleteComment(' . $this->id . ', \'' . $url . '\');" class="btn btn-default" title="Удалить"><i class="icon-trash"></i></button>';
		}
	}
	
	/**
	 * @todo Where use?
	 * @return string
	 */
	public function getButtonUpdate()
	{
		if ((Yii::app()->user->inRole(['admin']) || $this->username == UserInfo::inst()->userLogin))
		{
			$url = Yii::app()->controller->createUrl('comment/update',['id'=>$this->id]);			
			return '<button onclick="updateComment(\'' . $url . '\');" class="btn btn-default" title="Изменить" data-toggle="modal" data-target="#modal-comment"><i class="icon-pencil"></i></button>';
		}
	}
	
	
}
