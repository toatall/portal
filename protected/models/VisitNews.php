<?php

/**
 * This is the model class for table "{{visit_news}}".
 *
 * The followings are the available columns in table '{{visit_news}}':
 * @property integer $id
 * @property integer $id_parent
 * @property string $username
 * @property string $ip_address
 * @property string $hostname
 * @property string $session_id
 * @property string $date_create
 *
 * The followings are the available model relations:
 * @property News $idParent
 */
class VisitNews extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{visit_news}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_parent, date_create', 'required'),
			array('id_parent', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>250),
			array('ip_address, hostname', 'length', 'max'=>50),
			array('session_id', 'length', 'max'=>100),
			// The following rule is used by search().			
			array('id, id_parent, username, ip_address, hostname, session_id, date_create', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'id_parent' => 'Id Parent',
			'username' => 'Username',
			'ip_address' => 'Ip Address',
			'hostname' => 'Hostname',
			'session_id' => 'Session',
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
		$criteria->compare('hostname',$this->hostname,true);
		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('date_create',$this->date_create,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VisitNews the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Сохрание информации о пользователе,
	 * открывшем новость идентификатором $id_news
	 * @param int $id_news
	 * @uses NewsController::actionView()
	 */
	public static function saveVisit($id_news)
	{
		Yii::app()->db->createCommand('exec p_pr_visit_news @id_news=:id_news, @username=:username, @ip_address=:ip_address, @hostname=:hostname, @session_id=:session_id')
			->bindValue(':id_news', $id_news)
			->bindValue(':username', UserInfo::inst()->userLogin)
			->bindValue(':ip_address', UserInfo::inst()->clientIP)
			->bindValue(':hostname', UserInfo::inst()->clientHost)
			->bindValue(':session_id', session_id())
			->execute();
	}
	
	/**
	 * Save information about visit
	 * @param int $id
	 */
	public static function saveVisitMentor($id)
	{
	    Yii::app()->db->createCommand('exec p_pr_visit_mentor @id_post=:id_post, @username=:username, @ip_address=:ip_address, @hostname=:hostname, @session_id=:session_id')
    	    ->bindValue(':id_post', $id)
    	    ->bindValue(':username', UserInfo::inst()->userLogin)
    	    ->bindValue(':ip_address', UserInfo::inst()->clientIP)
    	    ->bindValue(':hostname', UserInfo::inst()->clientHost)
    	    ->bindValue(':session_id', session_id())
	       ->execute();
	}
	
}
