<?php

/**
 * This is the model class for table "{{log}}".
 *
 * The followings are the available columns in table '{{log}}':
 * @property integer $id
 * @property integer $id_user
 * @property string $username
 * @property string $model_name
 * @property integer $id_model
 * @property integer $operation
 * @property string $date_create
 * @property string $remote_ip
 * @property string $remote_host
 */
class Log extends CActiveRecord
{
	
	const OPERATION_CREATE = 0;
	const OPERATION_UPDATE = 1;
	const OPERATION_DEL = 2;
	
	
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, model_name, id_model, operation, date_create', 'required'),
			array('id_user, id_model, operation', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>250),
			array('model_name, remote_ip, remote_host', 'length', 'max'=>50),
			array('is_delete', 'boolean'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user, username, model_name, id_model, operation, date_create, 
				remote_ip, remote_host, is_delete', 'safe', 'on'=>'search'),
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
			'id_user' => 'Id User',
			'username' => 'Username',
			'model_name' => 'Model Name',
			'id_model' => 'Id Model',
			'operation' => 'Operation',
			'date_create' => 'Date Create',
			'remote_ip' => 'Remote Ip',
			'remote_host' => 'Remote Host',
			
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
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('id_model',$this->id_model);
		$criteria->compare('operation',$this->operation);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('remote_ip',$this->remote_ip,true);
		$criteria->compare('remote_host',$this->remote_host,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Запись лога
	 * @param int $operation
	 * @param string $model
	 * @param int $modelId
	 */
	public static function insertLog($model, $operation = null)
	{
		$remoteIP =  isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
		$remoteHost = ($remoteIP !== false) ? gethostbyaddr($remoteIP) : false;
		
		if ($operation === null)
		{
			if ($model->isNewRecord)
			{
				$operation = self::OPERATION_CREATE;
			}
			else
			{
				if (isset($model->date_delete) && ($model->date_delete !== null))
				{
					$operation = self::OPERATION_DEL;
				}
				else
				{
					$operation = self::OPERATION_UPDATE;
				}
			}
		}
		
		
		return Yii::app()->db->createCommand()
			->insert('{{log}}', array(
				'id_user' => (!Yii::app()->user->isGuest) ? Yii::app()->user->id : new CDbExpression('null'),
				'username' => (!Yii::app()->user->isGuest) ? Yii::app()->user->name : 'guest',
				'operation' => $operation,
				'model_name' => get_class($model),
				'id_model' => $model->id,
				'date_create' => new CDbExpression('getdate()'),
				'remote_ip' => ($remoteIP !== false) ? $remoteIP : new CDbExpression('null'),
				'remote_host' => ($remoteHost !== false) ? $remoteHost : new CDbExpression('null'),
				//is_delete
		));
	}
	
}
