<?php

/**
 * This is the model class for table "{{reestr_svt}}".
 *
 * The followings are the available columns in table '{{reestr_svt}}':
 * @property integer $id
 * @property string $code_no
 * @property string $date_fault
 * @property string $date_appeal_fku
 * @property string $fault_description
 * @property string $text_close
 * @property string $date_appeal_fku_ufns
 * @property string $device_fault
 * @property string $number_inventary
 * @property string $actions_ufns_fault
 * @property string $work_spares
 * @property string $date_acceptance_fku
 * @property string $date_acceptance_ufns
 * @property string $text_close
 * @property string $solved_fku
 * @property string $date_close
 * @property string $actions_ufns
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 * @property integer $status_code
 */
class ReestrSvt extends CActiveRecord
{
	
	public $message;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{reestr_svt}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			
			array('code_no, date_fault, device_fault, fault_description, number_inventary,
				date_appeal_fku, number_appeal_fku', 'required'),
			array('code_no', 'length', 'max'=>5),			
			array('device_fault', 'length', 'max'=>500),
			array('number_inventary, number_appeal_fku', 'length', 'max'=>30),
			array('work_spares, text_close', 'length', 'max'=>1000),
			array('date_appeal_fku, date_appeal_fku_ufns, actions_ufns_fault, date_acceptance_fku, 
				date_acceptance_ufns, actions_ufns, fault_description', 'safe'),
			
			// для отправки заявки в УФНС	
			array('work_spares', 'required', 'on'=>'requestUFNS'),
			array('id, code_no, date_fault, date_appeal_fku, text_close, device_fault, 
				number_inventary, actions_ufns_fault, date_acceptance_fku, date_acceptance_ufns, 
				text_close, date_close, actions_ufns, date_create, fault_description, 
				date_appeal_fku_ufns, solved_fku', 'unsafe', 'on'=>'requestUFNS'),
			
			// для отправки заявки в УФНС
			array('text_close', 'required', 'on'=>'requestClose'),
			array('id, code_no, date_fault, date_appeal_fku, date_appeal_fku_ufns, device_fault, 
				number_inventary, actions_ufns_fault, work_spares, date_acceptance_fku, date_acceptance_ufns, 
				date_close,	actions_ufns, date_create, date_edit, log_change, fault_description', 
				'unsafe', 'on'=>'requestClose'),
								
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code_no, date_fault, date_appeal_fku, text_close, date_appeal_fku_ufns, device_fault, 
				number_inventary, actions_ufns_fault, work_spares, date_acceptance_fku, date_acceptance_ufns, 
				text_close, solved_fku, date_close, actions_ufns, date_create, date_edit, log_change, 
				fault_description', 'safe', 'on'=>'search'),
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
			'organization' => array(self::HAS_ONE, 'Organization', array('code_no'=>'id_organization')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(		

			'id' => 'ИД',

			// Для ИФНС
			'code_no' => 'Код организации',
			'date_fault' => 'Дата неисправности',
			'device_fault' => 'Наименование оборудования',
			'number_inventary' => 'Инвентарный номер',
			'fault_description' => 'Описание неисправности',
			'date_appeal_fku' => 'Дата заявки в ФКУ',
			'number_appeal_fku' => 'Номер завки в ФКУ',

			// Для ФКУ	
			'date_acceptance_fku' => 'Дата приема заявки в ФКУ',
			'work_spares' => 'Заключение ФКУ',
			'solved_fku' => 'Решено ФКУ',
			'date_appeal_fku_ufns' => 'Дата обращения ФКУ в УФНС',

			// Для УФНС	
			'date_acceptance_ufns' => 'Дата приема заявку в УФНС',
			'actions_ufns' => 'Действия УФНС',			
				
			'date_close' => 'Дата закрытия заявки',
			'text_close' => 'Заключение',
						
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
			'log_change' => 'Журнал изменений',
			
			'status' => 'Статус',
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
		$criteria->compare('date_fault',$this->date_fault,true);
		$criteria->compare('date_appeal_fku',$this->date_appeal_fku,true);
		$criteria->compare('text_close',$this->text_close,true);
		$criteria->compare('date_appeal_fku_ufns',$this->date_appeal_fku_ufns,true);
		$criteria->compare('device_fault',$this->device_fault,true);
		$criteria->compare('number_inventary',$this->number_inventary,true);
		$criteria->compare('number_appeal_fku',$this->number_appeal_fku,true);
		$criteria->compare('work_spares',$this->work_spares,true);
		$criteria->compare('date_acceptance_fku',$this->date_acceptance_fku,true);
		$criteria->compare('date_acceptance_ufns',$this->date_acceptance_ufns,true);
		$criteria->compare('text_close',$this->text_close,true);
		$criteria->compare('solved_fku',$this->solved_fku,true);
		$criteria->compare('date_close',$this->date_close,true);
		$criteria->compare('actions_ufns',$this->actions_ufns,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('log_change',$this->log_change,true);
		$criteria->compare('fault_description',$this->fault_description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReestrSvt the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 */
	protected function afterFind()
	{
		$this->date_create = ConvertDate::find($this->date_create);
		$this->date_edit = ConvertDate::find($this->date_edit);		
		$this->date_fault = ConvertDate::find($this->date_fault, true);
		$this->date_appeal_fku = ConvertDate::find($this->date_appeal_fku,true);
		$this->date_appeal_fku_ufns = ConvertDate::find($this->date_appeal_fku_ufns,true);
		$this->date_acceptance_fku = ConvertDate::find($this->date_acceptance_fku);
		$this->date_acceptance_ufns = ConvertDate::find($this->date_acceptance_ufns);
		$this->date_close = ConvertDate::find($this->date_close);
		
		parent::afterFind();
	}
	
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::beforeSave()
	 */
	protected function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->date_create = new CDbExpression('getdate()');
		}
		else 
		{
			$this->date_edit = new CDbExpression('getdate()');
		}
		
		$this->log_change = LogChange::setLog($this->log_change, $this->status);	
		
		return parent::beforeSave();
	}
	
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterSave()
	 */
	protected function afterSave()
	{
		$this->saveMessage();
	}
	
	
	/**
	 * Статус заявки
	 * 
	 * @return string
	 * @author oleg
	 */
	public function getStatus()
	{
		$statuses = [
			1 => 'Направлена',
			2 => 'В работе',
			3 => 'Закрыта',
			4 => 'Отклонена',
		];
		
		return (isset($statuses[$this->status_code]) ? $statuses[$this->status_code] : 'неизвестно');
	}
	
	
	/**
	 * Цвет фона для столбца (actionAdmin::gridView)
	 * @return string
	 */
	public function getColor()
	{
		if ($this->date_close!==null)
		{
			return 'success';
		}
		elseif ($this->date_acceptance_ufns!==null || $this->date_acceptance_fku!==null)
		{
			return 'active';
		}
		elseif ($this->date_appeal_fku_ufns!==null || $this->date_appeal_fku!==null)
		{
			return 'warning';
		}	
	}
	
	
	/**
	 * Сохранение сообщения в дочерней таблице сообщений
	 */
	private function saveMessage()
	{
		$model = new ReestrSvtMessage();
		$model->id_reestr = $this->id;
		$model->msg = $this->message;
		$model->date_create = new CDbExpression('getdate()');
		$model->author = (isset(Yii::app()->user->name) ? Yii::app()->user->name : 'гость');
		$model->save();
	}
	
	
}
