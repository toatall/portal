<?php

/**
 * This is the model class for table "{{section}}".
 *
 * The followings are the available columns in table '{{section}}':
 * @property integer $id
 * @property string $name
 * @property string $module
 * @property boolean $use_organization
 * @property string $date_create
 * @property string $date_modification
 * @deprecated нужно это?
 */
class Section extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{section}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, module', 'required'),
			array('name, module', 'length', 'max'=>50),
			array('use_organization, date_create, date_modification', 'safe'),
			// The following rule is used by search().			
			array('id, name, module, use_organization, date_create, date_modification', 'safe', 'on'=>'search'),
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
			'id' => 'ИД',
			'name' => 'Наименование',
			'module' => 'Модуль',
			'use_organization' => 'Группировка по справочнику',
			'date_create' => 'Дата создания',
			'date_modification' => 'Дата изменения',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('module',$this->module,true);
		$criteria->compare('use_organization',$this->use_organization);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_modification',$this->date_modification,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Section the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    protected function afterFind()
    {
        $this->date_create = date('d.m.Y H:i:s', strtotime($this->date_create));
        $this->date_modification = date('d.m.Y H:i:s', strtotime($this->date_modification));
        parent::afterFind();
    }
    
    protected function beforeSave()
    {
        if ($this->isNewRecord)
            $this->date_create = new CDbExpression('getdate()');
        $this->date_modification = new CDbExpression('getdate()');        
        return parent::beforeSave();        
    }
    
    
    // дерево струкуры организаций
    public function getListOrganization($parent_id=0, $section_id, $readOnly = false)
    {                
        $data = array();
        $orgData = Organization::model()->findAll(array(
            'order'=>'sort ASC, name ASC',
            'condition'=>'id_parent=:id_parent', 
            'params'=>array(':id_parent'=>$parent_id)
        ));
        foreach ($orgData as $value)
        {            
            $data[] = array(
                'id'=>$value->id,
                'text'=>'<i class="icon-folder-open"></i>&nbsp;'                                   
                    .CHtml::checkBox('Section[Organization][]',
                    Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('{{section_organization}}')
                        ->where('id_section=:id_section AND id_organization=:id_organization',
                            array(
                                ':id_section'=>$section_id,
                                ':id_organization'=>$value->id,
                            )
                        )
                        ->queryRow(),                        
                    array(
                        'value'=>$value->id,
                        'id'=>CHtml::getIdByName('Section[Organization]['.$value->id.']'),                        
                        'onclick'=> $readOnly ? 'return false' : '',
                    ))
                    .'&nbsp;'
                    .CHtml::label($value->name, CHtml::getIdByName('Section[Organization]['.$value->id.']'),
                        array('style'=>'display:inline;')),                   
                'children'=>$this->getListOrganization($value->id, $section_id, $readOnly),
            );                    
        }
        return $data;
    }
    
    // сохранение связи структуры со справочником организации
    public function saveRelationOrganizations($orgs, $section_id)
    {        
        $command = Yii::app()->db->createCommand();
        $command->delete('{{section_organization}}', 'id_section=:id', array(':id'=>$section_id));
        
        if (count($orgs))
        {
            foreach ($orgs as $val)
            {                
                if (!is_numeric($section_id) || !is_numeric($val)) continue;
                $command->reset();
                $command->insert('{{section_organization}}', array(
                    'id_section'=>$section_id,
                    'id_organization'=>$val,
                    'date_create'=>new CDbExpression('getdate()'),
                ));                
            }
        }        
    }  
    
}
