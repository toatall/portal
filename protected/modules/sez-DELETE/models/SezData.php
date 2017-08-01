<?php

/**
 * This is the model class for table "{{sez_data}}".
 *
 * The followings are the available columns in table '{{sez_data}}':
 * @property integer $id
 * @property integer $id_sez
 * @property integer $id_author
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 * @property string $egrn_fid
 * @property string $egrn_fid_stat
 * @property string $egrn_inn_actual
 * @property string $egrn_date_inn_actual
 * @property string $egrn_inn_not_actual
 * @property string $egrn_date_inn_not_actual
 * @property string $egrn_inn_value_not_actual
 * @property string $egrn_fio
 * @property string $egrn_fio_update
 * @property string $egrn_date_birth
 * @property string $egrn_place_birth
 * @property string $egrn_date_dead
 * @property string $egrn_ogrnip
 * @property string $egrn_doc_identity_actual
 * @property string $egrn_doc_identity_not_actual
 * @property string $egrn_variant_write_ponil
 * @property string $egrn_address_actual_location
 * @property string $egrn_code_no_address_actual_location
 * @property string $egrn_date_reg_location
 * @property string $egrn_prev_address_location
 * @property string $egrn_code_no_prev_address_location
 * @property string $egrn_date_departures_address_location
 * @property string $object_type_reg
 * @property string $object_name_reg
 * @property string $objec_fid_reg
 * @property string $object_code_no_reg
 * @property string $object_address_reg
 * @property string $object_source_info_reg
 * @property string $object_date_register
 * @property string $object_date_unregister
 * @property string $object_casuse_unregister
 * @property string $krsb_code_no
 * @property string $krsb_kbk
 * @property string $krsb_oktmo
 * @property boolean $krsb_flag_open_close
 * @property string $krsb_saldo_calc
 *
 * The followings are the available model relations:
 * @property Sez $idSez
 * @property User $idAuthor
 */
class SezData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sez_data}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('egrn_fid, egrn_inn_actual, egrn_date_inn_actual, 
				egrn_fio, egrn_fio_update, egrn_date_birth, egrn_place_birth, egrn_date_dead, egrn_ogrnip, egrn_doc_identity_actual, 
				egrn_doc_identity_not_actual', 'required'),
			array('id_sez, id_author', 'numerical', 'integerOnly'=>true),
			array('egrn_fid, egrn_fio, egrn_fio_update, objec_fid_reg', 'length', 'max'=>250),
			array('egrn_fid_stat, egrn_variant_write_ponil, object_type_reg, object_source_info_reg, object_casuse_unregister', 'length', 'max'=>100),
			array('egrn_inn_actual, egrn_inn_not_actual, egrn_inn_value_not_actual', 'length', 'max'=>12),
			array('egrn_place_birth, egrn_address_actual_location, egrn_prev_address_location, object_name_reg, object_address_reg', 'length', 'max'=>500),
			array('egrn_ogrnip', 'length', 'max'=>32),
			array('egrn_doc_identity_actual, egrn_doc_identity_not_actual', 'length', 'max'=>200),
			array('egrn_code_no_address_actual_location, egrn_code_no_prev_address_location, object_code_no_reg, krsb_code_no', 'length', 'max'=>5),
			array('krsb_kbk, krsb_oktmo', 'length', 'max'=>20),
			array('krsb_saldo_calc', 'length', 'max'=>1),
			array('id_sez, id_author, date_create, date_edit, log_change', 'unsafe'),
				
			array('egrn_date_inn_not_actual, egrn_date_reg_location, egrn_date_departures_address_location, object_date_register, 
				object_date_unregister, krsb_flag_open_close', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_sez, id_author, date_create, date_edit, log_change, egrn_fid, egrn_fid_stat, egrn_inn_actual, egrn_date_inn_actual, 
				egrn_inn_not_actual, egrn_date_inn_not_actual, egrn_inn_value_not_actual, egrn_fio, egrn_fio_update, egrn_date_birth, egrn_place_birth, 
				egrn_date_dead, egrn_ogrnip, egrn_doc_identity_actual, egrn_doc_identity_not_actual, egrn_variant_write_ponil, 
				egrn_address_actual_location, egrn_code_no_address_actual_location, egrn_date_reg_location, egrn_prev_address_location, 
				egrn_code_no_prev_address_location, egrn_date_departures_address_location, object_type_reg, object_name_reg, objec_fid_reg, 
				object_code_no_reg, object_address_reg, object_source_info_reg, object_date_register, object_date_unregister, object_casuse_unregister, 
				krsb_code_no, krsb_kbk, krsb_oktmo, krsb_flag_open_close, krsb_saldo_calc', 'safe', 'on'=>'search'),
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
			'idSez' => array(self::BELONGS_TO, 'Sez', 'id_sez'),
			'idAuthor' => array(self::BELONGS_TO, 'User', 'id_author'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'id_sez' => 'ИД заявки ',
			'id_author' => 'Пользователь',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения (последняя)',
			'log_change' => 'Журнал изменений',
			'egrn_fid' => 'ФИДы ФЛ',
			'egrn_fid_stat' => 'Состояние ФИДа в ПОН ИЛ',
			'egrn_inn_actual' => 'ИНН ФЛ',
			'egrn_date_inn_actual' => 'Дата присвоения ИНН ФЛ',
			'egrn_inn_not_actual' => 'ИНН ФЛ (недействительный) ',
			'egrn_date_inn_not_actual' => 'Дата присвоения недействительн',
			'egrn_inn_value_not_actual' => 'Значение ИНН, который необходи',
			'egrn_fio' => 'ФИО',
			'egrn_fio_update' => 'ФИО (в случае изменения)',
			'egrn_date_birth' => 'Дата рождения',
			'egrn_place_birth' => 'Место рождения',
			'egrn_date_dead' => 'Дата смерти (при наличии)',
			'egrn_ogrnip' => 'ОГРНИП (в том числе и прекрати',
			'egrn_doc_identity_actual' => 'Документ, удостоверяющий лично',
			'egrn_doc_identity_not_actual' => 'Документы, удостоверяющие личн',
			'egrn_variant_write_ponil' => 'Варианты написания в ПОН ИЛ, к',
			'egrn_address_actual_location' => 'Актуальный адрес место жительс',
			'egrn_code_no_address_actual_location' => 'Код НО по актуальному месту жи',
			'egrn_date_reg_location' => 'Дата регистрации по месту жите',
			'egrn_prev_address_location' => 'Предыдущий адрес место жительс',
			'egrn_code_no_prev_address_location' => 'Код НО по предыдущему адресу м',
			'egrn_date_departures_address_location' => 'Дата выбытия по адресу место ж',
			'object_type_reg' => 'Сведения о типе объекта учета ',
			'object_name_reg' => 'Наименование объекта учета (кв',
			'objec_fid_reg' => 'ФИД объекта учета',
			'object_code_no_reg' => 'Код НО',
			'object_address_reg' => 'Адрес объекта учета',
			'object_source_info_reg' => 'Источник получения сведений об',
			'object_date_register' => 'Дата постановки на налоговый у',
			'object_date_unregister' => 'Дата снятия с налогового учета',
			'object_casuse_unregister' => 'Причина снятия',
			'krsb_code_no' => 'Код НО',
			'krsb_kbk' => 'КБК',
			'krsb_oktmo' => 'ОКТМО',
			'krsb_flag_open_close' => 'Признак: закрыта/открыта КРСБ',
			'krsb_saldo_calc' => 'Сальдо расчетов по состоянию н',
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
		$criteria->compare('id_sez',$this->id_sez);
		$criteria->compare('id_author',$this->id_author);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('log_change',$this->log_change,true);
		$criteria->compare('egrn_fid',$this->egrn_fid,true);
		$criteria->compare('egrn_fid_stat',$this->egrn_fid_stat,true);
		$criteria->compare('egrn_inn_actual',$this->egrn_inn_actual,true);
		$criteria->compare('egrn_date_inn_actual',$this->egrn_date_inn_actual,true);
		$criteria->compare('egrn_inn_not_actual',$this->egrn_inn_not_actual,true);
		$criteria->compare('egrn_date_inn_not_actual',$this->egrn_date_inn_not_actual,true);
		$criteria->compare('egrn_inn_value_not_actual',$this->egrn_inn_value_not_actual,true);
		$criteria->compare('egrn_fio',$this->egrn_fio,true);
		$criteria->compare('egrn_fio_update',$this->egrn_fio_update,true);
		$criteria->compare('egrn_date_birth',$this->egrn_date_birth,true);
		$criteria->compare('egrn_place_birth',$this->egrn_place_birth,true);
		$criteria->compare('egrn_date_dead',$this->egrn_date_dead,true);
		$criteria->compare('egrn_ogrnip',$this->egrn_ogrnip,true);
		$criteria->compare('egrn_doc_identity_actual',$this->egrn_doc_identity_actual,true);
		$criteria->compare('egrn_doc_identity_not_actual',$this->egrn_doc_identity_not_actual,true);
		$criteria->compare('egrn_variant_write_ponil',$this->egrn_variant_write_ponil,true);
		$criteria->compare('egrn_address_actual_location',$this->egrn_address_actual_location,true);
		$criteria->compare('egrn_code_no_address_actual_location',$this->egrn_code_no_address_actual_location,true);
		$criteria->compare('egrn_date_reg_location',$this->egrn_date_reg_location,true);
		$criteria->compare('egrn_prev_address_location',$this->egrn_prev_address_location,true);
		$criteria->compare('egrn_code_no_prev_address_location',$this->egrn_code_no_prev_address_location,true);
		$criteria->compare('egrn_date_departures_address_location',$this->egrn_date_departures_address_location,true);
		$criteria->compare('object_type_reg',$this->object_type_reg,true);
		$criteria->compare('object_name_reg',$this->object_name_reg,true);
		$criteria->compare('objec_fid_reg',$this->objec_fid_reg,true);
		$criteria->compare('object_code_no_reg',$this->object_code_no_reg,true);
		$criteria->compare('object_address_reg',$this->object_address_reg,true);
		$criteria->compare('object_source_info_reg',$this->object_source_info_reg,true);
		$criteria->compare('object_date_register',$this->object_date_register,true);
		$criteria->compare('object_date_unregister',$this->object_date_unregister,true);
		$criteria->compare('object_casuse_unregister',$this->object_casuse_unregister,true);
		$criteria->compare('krsb_code_no',$this->krsb_code_no,true);
		$criteria->compare('krsb_kbk',$this->krsb_kbk,true);
		$criteria->compare('krsb_oktmo',$this->krsb_oktmo,true);
		$criteria->compare('krsb_flag_open_close',$this->krsb_flag_open_close);
		$criteria->compare('krsb_saldo_calc',$this->krsb_saldo_calc,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SezData the static model class
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
		}
		else
		{
			$this->date_edit = new CDbExpression('getdate()');
		}
		
		$this->id_author = Yii::app()->user->isGuest ? 0 : Yii::app()->user->id;
		
		$this->log_change = LogChange::setLog($this->log_change, ($this->isNewRecord ? 'создание' : 'изменение'));
		
		return parent::beforeSave();
	}
	
	
}
