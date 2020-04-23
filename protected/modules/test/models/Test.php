<?php

/**
 * This is the model class for table "{{test}}".
 *
 * The followings are the available columns in table '{{test}}':
 * @property integer $id
 * @property string $name
 * @property string $date_start
 * @property string $date_end
 * @property integer $count_attempt
 * @property integer $count_questions
 * @property string $description
 * @property string $date_create
 * @property string $author
 *
 * The followings are the available model relations:
 * @property TestQuestion[] $testQuestions
 * @property TestResult[] $testResults
 */
class Test extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{test}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, date_start, date_end, count_attempt, count_questions', 'required'),
			array('count_attempt, count_questions', 'numerical', 'integerOnly'=>true),
			array('name, author', 'length', 'max'=>250),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, date_start, date_end, count_attempt, count_questions, description, date_create, author', 'safe', 'on'=>'search'),
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
			'testQuestions' => array(self::HAS_MANY, 'TestQuestion', 'id_test'),
			'testResults' => array(self::HAS_MANY, 'TestResult', 'id_test'),
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
			'count_attempt' => 'Количество попыток',
			'count_questions' => 'Количество вопросов (доступные при ответах)',
			'description' => 'Описание',
			'date_create' => 'Дата создания',
			'author' => 'Автор',
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
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('date_end',$this->date_end,true);
		$criteria->compare('count_attempt',$this->count_attempt);
		$criteria->compare('count_questions',$this->count_questions);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('author',$this->author,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Test the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @inheritDoc
     * @return bool
     */
    protected function beforeSave()
    {
        if ($this->isNewRecord)
        {
            $this->date_create = new CDbExpression('getdate()');
        }
        $this->author = Yii::app()->user->name;
        return parent::beforeSave();
    }

    /**
     * @inheritDoc
     */
    protected function afterFind()
    {
        parent::afterFind();
        /* @var $dateHelper DateHelper */
        $dateHelper = Yii::app()->dateHelper;
        $this->date_create = $dateHelper->asDateTime($this->date_create);
        $this->date_start = $dateHelper->asDate($this->date_start);
        $this->date_end = $dateHelper->asDate($this->date_end);
    }

    /**
     * Активный тест
     * @return bool
     */
    public function getActive()
    {
        $dateNow = time();
        $dateStart = strtotime($this->date_start);
        $dateEnd = strtotime($this->date_end);
        return $dateNow >= $dateStart && $dateNow <= $dateEnd;
    }


}
