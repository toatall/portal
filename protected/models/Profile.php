<?php

/**
 * This is the model class for table "{{profile}}".
 *
 * The followings are the available columns in table '{{profile}}':
 * @property integer $id
 * @property string $telephone
 * @property string $telephone_ip
 * @property string $name
 * @property string $post
 * @property string $rank
 * @property string $photo_file
 * @property string $about
 * @property string $status
 * @property string $date_create
 * @property string $date_edit
 * @property boolena $delete_image
 *
 * The followings are the available model relations:
 * @property User $idUser
 * 
 * @deprecated
 */
class Profile extends CActiveRecord
{
	
	public $delete_image;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{profile}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),			
			array('telephone, telephone_ip', 'length', 'max'=>50),
			array('name, status', 'length', 'max'=>500),
			array('rank, photo_file, post', 'length', 'max'=>250),
			array('delete_image', 'boolean'),
			array('photo_file', 'file', 
				'allowEmpty'=>true, 'maxSize'=>2*1024*1024, 'types'=>'gif,jpg,jpeg,png'),
			array('about, date_edit', 'safe'),			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, telephone, telephone_ip, name, post, rank, photo_file, 
					about, status, date_create, date_edit, delete_image', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',						
			'telephone' => 'Телефон',
			'telephone_ip' => 'Телефон IP',
			'name' => 'ФИО',
			'post' => 'Должность',
			'rank' => 'Чин',
			'photo_file' => 'Фотография',
			'about' => 'О себе',
			'status' => 'Статус',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',	
			'delete_image' => 'Удалить фотографию',
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
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('telephone_ip',$this->telephone_ip,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('post',$this->post,true);
		$criteria->compare('rank',$this->rank,true);
		$criteria->compare('photo_file',$this->photo_file,true);
		$criteria->compare('about',$this->about,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Profile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	protected function beforeSave()
	{
		if ($this->isNewRecord)
			$this->date_create = new CDbExpression('getdate()');			
		
		Log::insertLog($this);
		
		return parent::beforeSave();
	}
	
	protected function afterFind()
	{
		$this->date_create = ConvertDate::find($this->date_create);
		$this->date_edit = ConvertDate::find($this->date_edit);				
	}
	
	
	/**
	 * Сохранение информации о пользователе из ActiveDirectory
	 * @param unknown $modelUser
	 * @return NULL|Profile
	 * @deprecated
	 */
	public static function findSaveProfile($modelUser)
	{
		
		if ($modelUser === null) 
			return null;
		
		if ($model = self::model()->findByPk($modelUser->id) === null)
			$model = new self();
		
		$model->id = $modelUser->id;
		
		// попытка получить информацию из ActiveDirectory
		if ($modelUser !== null)
		{
			$ldapInfo = new LDAPInfo();
			$ldapInfo->getInfoAD($modelUser->username_windows);			
			if ($ldapInfo !== false)
			{
			
				$model->name =  ($ldapInfo->cn!=null ? $ldapInfo->cn : $ldapInfo->displayname);
				$model->telephone_ip = $ldapInfo->telephonenumber;
				$model->post = $ldapInfo->title;
				
				if ($model->validate() && $model->save())
					return $model;
			}
		}
		
		return null;
	}
	
	
	/**
	 * Получение ФИО пользователя по его логину
	 * Если ФИО не найдено, то возвращается логин
	 * @param string $login логин пользователя
	 * @return string
	 * @author oleg
	 */
	public static function nameByLogin($login)
	{
	    $profile = Yii::app()->db->createCommand()
	       ->from('{{profile}} profile')
	       ->join('{{user}} user', 'profile.id=[user].id')
	       ->where('[user].username_windows=:username_windows', [':username_windows'=>$login])
	       ->queryRow();
	    return ($profile!==null && isset($profile['name'])) ? $profile['name'] : $login;
	}
	
	
	/*
	
	public static function autoSave(User $modelUser)
	{
		$model = new self();
		$model->id = $modelUser->id;
		
		// попытка получить информацию из ActiveDirectory		
		if ($modelUser !== null)
		{
			$ldapInfo = LDAPInfo::getInfoAD($modelUser->username_windows);
			if ($ldapInfo !== false)
			{
				$model->name = (isset($ldapInfo['sn'][0]) ? $ldapInfo['sn'][0].' ' : '').
				(isset($ldapInfo['givenname'][0]) ? $ldapInfo['givenname'][0] : '');
				$model->telephone_ip = (isset($ldapInfo['telephonenumber'][0])
						? $ldapInfo['telephonenumber'][0] : '');
				$model->post = (isset($ldapInfo['title'][0]) ? $ldapInfo['title'][0] : '');
					
			}
		}
		
		return ($model->validate() && $model->save());
		
	}*/
	
}
