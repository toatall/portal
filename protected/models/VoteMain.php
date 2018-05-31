<?php

/**
 * This is the model class for table "{{vote_main}}".
 *
 * The followings are the available columns in table '{{vote_main}}':
 * @property integer $id
 * @property string $name
 * @property string $date_start
 * @property string $date_end
 * @property string $organizations
 * @property boolean $multi_answer
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 * @property boolean $on_general_page
 * @property string $description
 *
 * The followings are the available model relations:
 * @property VoteQuestion[] $voteQuestions
 */
class VoteMain extends CActiveRecord
{
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{vote_main}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, date_start, date_end, organizations', 'required'),
			array('name', 'length', 'max'=>200),
			array('organizations', 'length', 'max'=>100),		    
			array('multi_answer, date_edit, orgList, on_general_page, description', 'safe'),
			// The following rule is used by search().			
			array('id, name, date_start, date_end, organizations, multi_answer, date_create, date_edit, on_general_page, description', 'safe', 'on'=>'search'),
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
			'voteQuestions' => array(self::HAS_MANY, 'VoteQuestion', 'id_main'),
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
			'date_start' => 'Дата начала',
			'date_end' => 'Дата окончания',
			'organizations' => 'Организации',
			'multi_answer' => 'Отвечать на все вопросы?',
		    'on_general_page' => 'На главной странице',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
			'log_change' => 'Журнал изменений',
		    'description' => 'Описание',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_end',$this->date_end,true);
		$criteria->compare('organizations',$this->organizations,true);
		$criteria->compare('multi_answer',$this->multi_answer);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('log_change',$this->log_change,true);
		$criteria->compare('on_general_page',$this->on_general_page);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VoteMain the static model class
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
	    if ($this->isNewRecord)
	    {
	        $this->date_create = new CDbExpression('getdate()');	        
	    }
	    else
	    {
	        $this->date_edit = new CDbExpression('getdate()');
	    }
	    $this->log_change = Log::setLog($this->log_change, ($this->isNewRecord ? 'создание' : 'изменение'));
	    
	    return parent::beforeSave();
	}
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 */
	protected function afterFind()
	{
	    parent::afterFind();
	    $this->date_create = DateHelper::explodeDateTime($this->date_create);
	    $this->date_edit = DateHelper::explodeDateTime($this->date_edit);
	    $this->date_start = DateHelper::explodeDate($this->date_start);
	    $this->date_end = DateHelper::explodeDate($this->date_end);
	}
	
	/**
	 * Список организаций доступных для просмотра
	 * @return array
	 */
	public function getOrgList()
	{
	    return explode('/', $this->organizations);
	}
	
	/**
	 * Список организаций для сохранения
	 * @param array $value
	 */
	public function setOrgList($value)
	{
	    if (!is_array($value))
            $value = array($value);	    
	    $this->organizations = implode('/', $value);	    
	}
	
	/**
	 * Признак того, что голосование окончено
	 * @return boolean
	 */
	public function getEndVote()
	{
	    return (strtotime(date('d.m.Y')) > strtotime($this->date_end));	        
	}
	
	/**
	 * Признак того, что пользователь уже голосовал
	 * @return boolean 
	 */
	public function getIsVoted()
	{
	    return Yii::app()->db->createCommand(
	        'select count(t.id) from {{vote_answer}} t
                join {{vote_question}} t_q on t_q.id=t.id_question
             where t_q.id_main=:id_main and t.user_login=:user_login'
	        )
	       ->bindValue(':id_main', $this->id)
	       ->bindValue(':user_login', UserInfo::inst()->userLogin)
	       ->queryScalar();
	}
	
	/**
	 * Всего количество голосов
	 * @return integer
	 */
	public function getCountAnswer()
	{
	    return Yii::app()->db->createCommand(
	        'select count(t.id) from {{vote_answer}} t
                join {{vote_question}} t_q on t_q.id=t.id_question
             where t_q.id_main=:id_main'
	        )
	        ->bindValue(':id_main', $this->id)	        
	        ->queryScalar();
	}
	
	/**
	 * Максимальное количество голосов
	 * @return integer
	 */
	public function getCountMax()
	{
	    return Yii::app()->db->createCommand(
	        'select max(count_votes) from {{vote_question}} 
             where id_main=:id_main'
	        )
	        ->bindValue(':id_main', $this->id)
	        ->queryScalar();
	}
	
}
