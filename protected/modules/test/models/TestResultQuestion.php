<?php

/**
 * This is the model class for table "{{test_result_question}}".
 *
 * The followings are the available columns in table '{{test_result_question}}':
 * @property integer $id
 * @property integer $id_test_result
 * @property integer $id_test_question
 * @property integer $weight
 * @property string $date_create
 * @property  bool $is_right
 *
 * The followings are the available model relations:
 * @property TestResultAnswer[] $testResultAnswers
 * @property TestQuestion $idTestQuestion
 * @property TestResult $idTestResult
 */
class TestResultQuestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{test_result_question}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_create', 'required'),
			array('id_test_result, id_test_question, weight', 'numerical', 'integerOnly'=>true),
            array('is_right', 'boolean'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_test_result, id_test_question, weight, date_create', 'safe', 'on'=>'search'),
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
			'testResultAnswers' => array(self::HAS_MANY, 'TestResultAnswer', 'id_test_result_question'),
			'idTestQuestion' => array(self::BELONGS_TO, 'TestQuestion', 'id_test_question'),
			'idTestResult' => array(self::BELONGS_TO, 'TestResult', 'id_test_result'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_test_result' => 'Id Test Result',
			'id_test_question' => 'Id Test Question',
			'weight' => 'Weight',
			'date_create' => 'Date Create',
            'is_right' => 'is_right',
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
		$criteria->compare('id_test_result',$this->id_test_result);
		$criteria->compare('id_test_question',$this->id_test_question);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('date_create',$this->date_create,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TestResultQuestion the static model class
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
        $this->date_create = new CDbExpression('getdate()');
        return parent::beforeSave();
    }

    /**
     *
     * @param $answer string|array
     * @return int
     * @throws CException
     */
    public function saveAnswers($answer)
    {
        /* @var $command CDbCommand */
        $command = Yii::app()->db->createCommand();
        // получение общего количества баллов
        $weight = $command->select('sum(weight)')->from('{{test_answer}}')->where(['in', 'id', $answer])->queryScalar();

        if (is_array($answer))
        {
            // сохранение ответов в таблице
            foreach ($answer as $item)
            {
                $command->insert('{{test_result_answer}}', [
                    'id_test_result_question' => $this->id,
                    'id_test_answer' => $item,
                    'date_create' => new CDbExpression('getdate()'),
                ]);
            }
        }
        else
        {
            // сохранение ответов в таблице
            $command->insert('{{test_result_answer}}', [
                'id_test_result_question' => $this->id,
                'id_test_answer' => $answer,
                'date_create' => new CDbExpression('getdate()'),
            ]);
        }

        return $this->weight == $weight ? 1 : 0;
    }

}
