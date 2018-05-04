<?php

/**
 * This is the model class for table "{{jornal_request}}".
 *
 * The followings are the available columns in table '{{jornal_request}}':
 * @property integer $id
 * @property string $code_no
 * @property string $ifns_ufns_date
 * @property string $ifns_ufns_number
 * @property string $ufns_fns_date
 * @property string $ufns_fns_number
 * @property string $fns_ufns_date
 * @property string $fns_ufns_number
 * @property string $ufns_ifns_date
 * @property string $ufns_ifns_number
 * @property string $date_execution
 * @property string $description
 * @property integer $status
 * @property string $date_create
 * @property string $log_access
 * 
 * @deprecated
 */
class JornalRequest extends CActiveRecord
{
    /**
     * Флаг отвечающий за дополнительные настройки прав
     * @var bool
     */
    public $useOptionalAccess = false; 
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{jornal_request}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code_no', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('code_no', 'length', 'max'=>4),
			array('ifns_ufns_number, ufns_fns_number, fns_ufns_number, ufns_ifns_number', 'length', 'max'=>50),
			array('log_change', 'length', 'max'=>5000),
			array('ifns_ufns_date, ufns_fns_date, fns_ufns_date, ufns_ifns_date, date_execution, description, date_create', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code_no, ifns_ufns_date, ifns_ufns_number, ufns_fns_date, ufns_fns_number, fns_ufns_date, 
                fns_ufns_number, ufns_ifns_date, ufns_ifns_number, date_execution, description, status, 
                date_create, log_change', 'safe', 'on'=>'search'),
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
			'id' => 'УН',
			'code_no' => 'Код НО',
			'ifns_ufns_date' => 'Дата исх. запроса от ИФНС',
			'ifns_ufns_number' => 'Номер исх. запроса от ИФНС',
			'ufns_fns_date' => 'Дата запроса в ФНС',
			'ufns_fns_number' => 'Номер запроса в ФНС',
			'fns_ufns_date' => 'Дата исх. запроса от ФНС',
			'fns_ufns_number' => 'Номер исх. запроса от ФНС',
			'ufns_ifns_date' => 'Дата исх. запроса в ИФНС',
			'ufns_ifns_number' => 'Номер исх. запроса в ИФНС',
			'date_execution' => 'Срок исполнения',
			'description' => 'Примечание',
			'status' => 'Статус',
			'date_create' => 'Дата создания',
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
		$criteria->compare('ifns_ufns_date',$this->ifns_ufns_date,true);
		$criteria->compare('ifns_ufns_number',$this->ifns_ufns_number,true);
		$criteria->compare('ufns_fns_date',$this->ufns_fns_date,true);
		$criteria->compare('ufns_fns_number',$this->ufns_fns_number,true);
		$criteria->compare('fns_ufns_date',$this->fns_ufns_date,true);
		$criteria->compare('fns_ufns_number',$this->fns_ufns_number,true);
		$criteria->compare('ufns_ifns_date',$this->ufns_ifns_date,true);
		$criteria->compare('ufns_ifns_number',$this->ufns_ifns_number,true);
		$criteria->compare('date_execution',$this->date_execution,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('log_change',$this->log_change,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return JornalRequest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
    
    protected function beforeSave()
    {
        $this->ifns_ufns_date = ($this->ifns_ufns_date=='') ? new CDbExpression('NULL')
            : new CDbExpression("STR_TO_DATE('".$this->ifns_ufns_date."','%d.%m.%Y')");
        $this->ufns_fns_date = ($this->ufns_fns_date=='') ? new CDbExpression('NULL')
            : new CDbExpression("STR_TO_DATE('".$this->ufns_fns_date."','%d.%m.%Y')");
        $this->fns_ufns_date = ($this->fns_ufns_date=='') ? new CDbExpression('NULL')
            : new CDbExpression("STR_TO_DATE('".$this->fns_ufns_date."','%d.%m.%Y')");    
        $this->ufns_ifns_date = ($this->ufns_ifns_date=='') ? new CDbExpression('NULL')
            : new CDbExpression("STR_TO_DATE('".$this->ufns_ifns_date."','%d.%m.%Y')");
        $this->date_execution = ($this->date_execution=='') ? new CDbExpression('NULL')
            : new CDbExpression("STR_TO_DATE('".$this->date_execution."','%d.%m.%Y')");    
        /*                   
        if ($this->ifns_ufns_date=='')  $this->ifns_ufns_date   = new CDbExpression('NULL');
        if ($this->ufns_fns_date=='')   $this->ufns_fns_date    = new CDbExpression('NULL');
        if ($this->fns_ufns_date=='')   $this->fns_ufns_date    = new CDbExpression('NULL');
        if ($this->ufns_ifns_date=='')  $this->ufns_ifns_date   = new CDbExpression('NULL');
        if ($this->date_execution=='')  $this->date_execution   = new CDbExpression('NULL'); 
        */       
        if ($this->isNewRecord)
            $this->date_create = new CDbExpression('NOW()');
        $this->log_change = LogChange::setLog($this->log_change,
            ($this->isNewRecord ? 'создание' : 'изменение'));
        
        return parent::beforeSave();        
    }
    
    protected function afterFind()
    {        
        $this->ifns_ufns_date = ($this->ifns_ufns_date!='') 
            ? date('d.m.Y', strtotime($this->ifns_ufns_date)) : '';
        $this->ufns_fns_date = ($this->ufns_fns_date!='') 
            ? date('d.m.Y', strtotime($this->ufns_fns_date)) : '';
        $this->fns_ufns_date = ($this->fns_ufns_date!='')
            ? date('d.m.Y', strtotime($this->fns_ufns_date)) : '';
        $this->ufns_ifns_date = ($this->ufns_ifns_date!='')
            ? date('d.m.Y', strtotime($this->ufns_ifns_date)) : '';
        $this->date_execution = ($this->date_execution!='') 
            ? date('d.m.Y', strtotime($this->date_execution)) : '';
        parent::afterFind();
    }
    
    
}
