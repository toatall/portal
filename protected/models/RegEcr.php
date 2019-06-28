<?php

/**
 * This is the model class for table "{{reg_ecr}}".
 *
 * The followings are the available columns in table '{{reg_ecr}}':
 * @property integer $id
 * @property string $code_org
 * @property string $date_reg
 * @property integer $count_create
 * @property integer $count_vote
 * @property integer $avg_eval_a_1_1
 * @property integer $avg_eval_a_1_2
 * @property integer $avg_eval_a_1_3
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string $date_delete
 */
class RegEcr extends CActiveRecord
{
    
    public $date1;
    public $date2;
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{reg_ecr}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('code_org, date_reg, count_create, count_vote, avg_eval_a_1_1, avg_eval_a_1_2, avg_eval_a_1_3', 'required'),
            array('code_org, date_reg', 'checkExists'),
            array('count_create, count_vote, avg_eval_a_1_1, avg_eval_a_1_2, avg_eval_a_1_3', 'numerical', 'integerOnly'=>true),
            array('code_org', 'length', 'max'=>5),
            array('author', 'length', 'max'=>250),
            array('date_create, date_update, date_delete', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code_org, date_reg, count_create, count_vote, avg_eval_a_1_1, avg_eval_a_1_2, avg_eval_a_1_3, author, date_create, date_update, '
                . 'date_delete, date1, date2', 'safe', 'on'=>'search'),
        );
    }
    
    /**
     * Правило проверки
     * Не должно быть записи с одинаковыми полями code_org и date_reg
     * @param string $attribute
     */
    public function checkExists($attribute)
    {           
        if ($this->isNewRecord && 
            $this->exists('code_org=:code_org and convert(varchar,date_reg,104)=convert(varchar,:date_reg,104) and date_delete is null', [
                ':code_org' => $this->code_org,
                ':date_reg' => $this->date_reg,
        ]))        
        {            
            $this->addError($attribute, "Запись с ИФНС: $this->code_org и датой {$this->date_reg} уже существует!");
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'organization' => array(self::HAS_ONE, 'Organization', array('code' => 'code_org')),
            'user' => array(self::HAS_ONE, 'User', array('username_windows' => 'author')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => '#',
            'code_org' => 'Наименование НО (по месту постановки на учет)',
            'date_reg' => 'Дата',
            'count_create' => 'Кол-во вновь созданных ООО',
            'count_vote' => 'Кол-во опрошенных',
            'avg_eval_a_1_1' => 'Средняя оценка А 1.1',
            'avg_eval_a_1_2' => 'Средняя оценка А 1.2',
            'avg_eval_a_1_3' => 'Средняя оценка А 1.3',
            'author' => 'Автор',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
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
        
        $criteria->addCondition('date_delete is null');
        $criteria->compare('id',$this->id);
        $criteria->compare('code_org',$this->code_org,true);
        $criteria->compare('convert(varchar,date_reg,104)',$this->date_reg,false);
        $criteria->compare('count_create',$this->count_create);
        $criteria->compare('count_vote',$this->count_vote);
        $criteria->compare('avg_eval_a_1_1',$this->avg_eval_a_1_1);
        $criteria->compare('avg_eval_a_1_2',$this->avg_eval_a_1_2);
        $criteria->compare('avg_eval_a_1_3',$this->avg_eval_a_1_3);
        $criteria->compare('author',$this->author,true);
        $criteria->compare('date_create',$this->date_create,true);
        $criteria->compare('date_update',$this->date_update,true);
        $criteria->compare('date_reg', '>=' . $this->date1);
        $criteria->compare('date_reg', '<=' . $this->date2);
        

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => array('defaultOrder' => 'date_reg desc, code_org asc'),
        ));
    }
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RegEcr the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * {@inheritDoc}
     * @see CActiveRecord::beforeSave()
     */
    protected function beforeSave() 
    {
        if (!parent::beforeSave())
        {
            return false;
        }
        
        $this->author = Yii::app()->user->name;
        if ($this->isNewRecord) 
        {
            $this->date_create = new CDbExpression('getdate()');            
        }
        $this->date_update = new CDbExpression('getdate()');
        
        return true;
    }
    
    /**
     * {@inheritDoc}
     * @see CActiveRecord::afterFind()
     */
    protected function afterFind() 
    {
        parent::afterFind();
        $this->date_reg = date('d.m.Y', strtotime($this->date_reg));
    }


    /**
     * Dropdownlist for '_form' view
     * @return array
     */
    public function getDropDownIfns()
    {
        $org = Yii::app()->db->createCommand()
            ->select("code, (code + ' - ' + name) as name")
            ->from('{{organization}}')
            ->where('code<>:code', [':code'=>'8600'])
            ->order('sort asc')
            ->queryAll();
        return CHtml::listData($org, 'code', 'name');
    }
    
    /**
    * Идентификатор структуры
    * @see Tree
    * @return int|null
    */
    public function getTreeId()
    {
        $modelTree = Tree::model()->find('module=:module',[':module'=>'regecr']);
        if ($modelTree !== null && count($modelTree)>0)
        {
            return $modelTree->id;
        }
        return null;
    }
        
}
