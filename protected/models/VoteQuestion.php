<?php

/**
 * This is the model class for table "{{vote_question}}".
 *
 * The followings are the available columns in table '{{vote_question}}':
 * @property integer $id
 * @property integer $id_main
 * @property string $text_question
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 * @property integer $count_votes
 *
 * The followings are the available model relations:
 * @property VoteAnswer[] $voteAnswers
 * @property VoteMain $idMain
 */
class VoteQuestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{vote_question}}';
	}
    
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::defaultScope()
	 */
	public function defaultScope()
	{
	    return array('order'=>'count_votes desc, text_question asc');
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text_question', 'required'),
			array('id_main, count_votes', 'numerical', 'integerOnly'=>true),		  
			array('date_edit', 'safe'),
			// The following rule is used by search().			
			array('id, id_main, text_question, date_create, date_edit, count_votes', 'safe', 'on'=>'search'),
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
			'voteAnswers' => array(self::HAS_MANY, 'VoteAnswer', 'id_question'),
			'idMain' => array(self::BELONGS_TO, 'VoteMain', 'id_main'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'id_main' => 'ИД родителя',
			'text_question' => 'Текст вопроса',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
			'log_change' => 'Журнал изменений',
		    'count_votes' => 'Количество голосов',
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
		$criteria->compare('id_main',$this->id_main);
		$criteria->compare('text_question',$this->text_question,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('log_change',$this->log_change,true);
		$criteria->compare('count_votes',$this->count_votes);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VoteQuestion the static model class
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
	}
	
	/**
	 * Сохранение голоса
	 * @param integer $idMain
	 * @param integer $votes
	 * @return boolean
	 */
	public static function saveAnswer($votes)
	{
	    if (!is_array($votes))
	        return false;
	    
	    foreach ($votes as $vote)
	    {
	        if (!is_numeric($vote))
	            continue;
	        
	        Yii::app()->db->createCommand()
	           ->insert('{{vote_answer}}', [
	               'id_question'=>$vote,
	               'user_login'=>UserInfo::inst()->userLogin,
	               'date_create'=>new CDbExpression('getdate()'),
	           ]);
	    }
	}
	
}
