<?php

/**
 * This is the model class for table "{{organization}}".
 *
 * The followings are the available columns in table '{{organization}}':
 * @property integer $code
 * @property string $name
 * @property integer $sort
 * @property string $date_create
 * @property string $date_edit
 */
class Organization extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{organization}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, name', 'required'),
            array('code', 'unique', 'attributeName'=>'code', 'className'=>'Organization'),
			array('sort', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>4),
            array('name', 'length', 'max'=>250),
			array('date_create, date_edit', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('code, name, sort, date_create, date_edit', 'safe', 'on'=>'search'),
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
			'code' => 'Код',
			'name' => 'Наименование',
			'sort' => 'Сортировка',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
			'FullName' => 'Наименование НО',
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

		$criteria->compare('code',$this->code);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);                
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'code asc',
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Organization the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
    protected function beforeSave()
    {        
        if ($this->isNewRecord)                   
            $this->date_create = new CDbExpression('NOW()');                    
        $this->date_edit = new CDbExpression('NOW()');
        return parent::beforeSave();
    }
    
    protected function afterFind()
    {        
        $this->date_create = date('d.m.Y H:i:s', strtotime($this->date_create));
        $this->date_edit = date('d.m.Y H:i:s', strtotime($this->date_edit));
        parent::afterFind();
    }
    
    public function getFullName()
    {
        return $this->code.' ('.$this->name.')';
    }
    
    
    public static function getSubMenu()
    {
        $model = Organization::model()->findAll(array(
            'condition'=>"code<>'8600'",
            'order'=>'sort asc',
        ));
        
        $resultArray = array();
        foreach ($model as $value)
        {
            $resultArray[] = array(
                'label'=>$value->name,
                'url'=>array('news/index', 'organization'=>$value->code),
            );
        }
        return $resultArray;
    }
    
    
    /**
     * Проверка на присутствие кода организации в имени логина пользователя
     * 
     * @param string $username
     * @return Organization|null
     * @author oleg
     * @version 16.05.2016 - create
     * 			21.02.2017 - refactoring
     */
    public static function codeOrganizationByUsernameWindows($username)
    {    	
    	if (preg_match('/n{0,}\d{4}/', $username, $matches))
    	{
    		if (isset($matches[0]) && !is_array($matches[0]) && strlen($matches[0])>0)
	    		return Organization::model()->findByPk($matches[0]);
    	}
    	return null;
    }
    
    
    /**
     * Получение пользователем текущей орагнизации (User.current_organization)
     * 
     * Если у пользователя по какой-либо причине отсутсвует доступ
     * к текущей организации, то выбирается первая доступная пользователю организации
     * 
     * @author oleg
     * @version 17.05.2016
     */
    public static function loadCurrentOrganization()
    {
    	// если уже присвоен код организации, то выходим
    	if (isset(Yii::app()->session['organization'])) return;
    	
		// выполняем поиск текущего пользователя
    	$userModel = User::model()->findByPk(Yii::app()->user->id);
    	    	    	
    	$userCurrentOrganization = isset($userModel->current_organization) 
    		&& !empty($userModel->current_organization)	
    			? $userModel->current_organization : null;
    	
    	// проверка прав у пользователя к текущей организации
    	if (!User::checkRightOrganization($userCurrentOrganization))
    	{
    		$userCurrentOrganization = null;
    	}
    	else
    	{
    		Yii::app()->session['organization'] = $userCurrentOrganization;
    	}
    	    	
    	// если нет доступа к текущей организации
    	if ($userCurrentOrganization===null)
    	{
    		if (isset($userModel->organization))
    		{
    			
    			if (isset($userModel->organization[0]->code))
    			{
    				$userCurrentOrganization = $userModel->organization[0]->code;
    				User::changeOrganization($userCurrentOrganization);
    			}
    		}    		
    	}     	    
    }
    
    
    
}
