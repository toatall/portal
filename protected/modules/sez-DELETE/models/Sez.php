<?php

/**
 * This is the model class for table "{{sez}}".
 *
 * The followings are the available columns in table '{{sez}}':
 * @property integer $id
 * @property string $code_no
 * @property integer $type_sez
 * @property integer $status
 * @property string $message
 * @property integer $id_author
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 *
 * The followings are the available model relations:
 * @property User $idAuthor
 * @property Organization $codeNo
 * @property SezCorr[] $sezCorrs
 * @property SezData[] $sezDatas
 * 
 * 
 * ----------------------------------
 * > Статусы:
 * ----------------------------------
 * 0 - черновик (не отправлена)
 * 1 - заявка отправлена (в отдел РНП)
 * 2 - заявка принята (отделом РНП)
 * 3 - заявка отклонена (отделом РНП)
 * 4 - 
 * 
 */
class Sez extends CActiveRecord
{
	
	
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sez}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_sez, code_no', 'required'),
			array('status, id_author', 'numerical', 'integerOnly'=>true),
			array('code_no', 'length', 'max'=>5),
			array('message', 'safe'),
			array('id, id_author, date_create, date_edit, log_change, status', 'unsafe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code_no, type_sez, status, message, id_author, date_create, 
				date_edit, log_change', 'safe', 'on'=>'search'),
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
			'idAuthor' => array(self::BELONGS_TO, 'User', 'id_author'),
			'codeNo' => array(self::BELONGS_TO, 'Organization', 'code_no'),
			'sezCorrs' => array(self::HAS_MANY, 'SezCorr', 'id_sez'),
			'sezDatas' => array(self::HAS_MANY, 'SezData', 'id_sez'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'code_no' => 'Код НО',
			'type_sez' => 'Тип заявки',
			'TypeSezText' => 'Тип заявки',
			'status' => 'Статус',
			'statusText' => 'Статус',
			'message' => 'Сообщение',
			'id_author' => 'Автор',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
			'log_change' => 'Журнал изменений',
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
		$criteria->compare('code_no',$this->code_no,true);
		$criteria->compare('type_sez',$this->type_sez);
		$criteria->compare('status',$this->status);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('id_author',$this->id_author);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('log_change',$this->log_change,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sez the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * Event before save into db
	 * @return parent::beforeSave()
	 * @author oleg
	 * @version 14.03.2017
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->date_create = new CDbExpression('getdate()');
			$this->status = 0;
		}
		else
		{
			$this->date_edit = new CDbExpression('getdate()');
		}
	
		$this->id_author = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
	
		$this->log_change = LogChange::setLog($this->log_change, ($this->isNewRecord ? 'создание' : 'изменение'));
	
		return parent::beforeSave();
	}
	
	
	/**
	 * Тип заявок в виде текста
	 * @return string[]
	 */
	public static function getlistTypeSez()
	{
		return array(
			'1' => 'Разделение физических лиц, «слитых» в ПОН ИЛ и в ЦУНе АИС «Налог-3», а также не состоящих на налоговом учете в ЦУНе АИС «Налог-3»',
			'2' => 'Устранение дубля физического лица и признание значения ИНН недействительным',
			'3' => 'Исключение вариантов написания в ПОН ИЛ по физическим лицам',
		);
	}
	
	public function getTypeSezText()
	{
		return self::getlistTypeSez()[$this->type_sez];
	}
	
	
	/**
	 * Текстовый статус
	 * @return string
	 */
	public function getStatusText()
	{
		switch ($this->status)
		{
			case 0: return 'Черновик'; break;
			
			default: return 'Неизвестно'; break;
		}
	}
	
}
