<?php

/**
 * This is the model class for table "{{test_result}}".
 *
 * The followings are the available columns in table '{{test_result}}':
 * @property integer $id
 * @property integer $id_test
 * @property string $username
 * @property string $org_code
 * @property string $date_create
 *
 * The followings are the available model relations:
 * @property Test $idTest
 * @property TestResultQuestion[] $testResultQuestions
 */
class TestResult extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{test_result}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('org_code, date_create', 'required'),
			array('id_test', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>250),
			array('org_code', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_test, username, org_code, date_create', 'safe', 'on'=>'search'),
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
			'idTest' => array(self::BELONGS_TO, 'Test', 'id_test'),
			'testResultQuestions' => array(self::HAS_MANY, 'TestResultQuestion', 'id_test_result'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_test' => 'Id Test',
			'username' => 'Username',
			'org_code' => 'Org Code',
			'date_create' => 'Date Create',
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
		$criteria->compare('id_test',$this->id_test);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('org_code',$this->org_code,true);
		$criteria->compare('date_create',$this->date_create,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TestResult the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Количество попыток, использованных текущим пользователем
     * @param $idTest
     * @return CDbDataReader|false|mixed|string
     */
	public static function countUserAttempts($idTest)
    {
        return self::model()->count('id_test=:id_test and username=:username', [
            ':id_test' => $idTest,
            ':username' => Yii::app()->user->name,
        ]);
    }

    /**
     * Сохранение информации в БД
     * Вернуть результат пользователю
     * @param $result
     * @return bool
     * @throws Exception
     */
    public function saveResult($result)
    {
        $fails = [];
        if (!isset($result['id'])) {
            $fails[] = "Не найден элемент id!";
        }
        if (!isset($result['questions'])) {
            $fails[] = "Не найден элемент questions!";
        }
        if (!isset($result['answers'])) {
            $result['answers'] = [];
        }
        if (!is_array($result['questions'])) {
            $fails[] = "Элемент questions не является массивом!";
        }
        if (!is_array($result['answers'])) {
            $result['answers'] = [];
        }

        if ($fails)
        {
            throw new Exception(implode('<br />', $fails));
        }

        $model = new TestResult();
        $model->id_test = $result['id'];
        $model->username = Yii::app()->user->name;
        $model->org_code = UserInfo::inst()->orgCode;
        $model->date_create = new CDbExpression('getdate()');
        if (!$model->save())
        {
            $errors = implode('<br />', implode('<br />', $model->getErrors()));
            throw new Exception('Не удалось сохранить данные о тесте. ' . $errors);
        }

        $questions = $result['questions'];
        $answers = $result['answers'];

        /* @var $db CDbConnection */
        $db = Yii::app()->db;

        // Результат теста
        $result = [
            'questions' => 0,
            'rightAnswers' => 0,
        ];

        // найти все вопросы
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $questions);
        $modelQuestions = TestQuestion::model()->findAll($criteria);
        /* @var $modelQuestion TestQuestion */
        foreach ($modelQuestions as $modelQuestion)
        {
            $result['questions'] += 1;

            $modelResultQuestion = new TestResultQuestion();
            $modelResultQuestion->id_test_result = $model->id;
            $modelResultQuestion->id_test_question = $modelQuestion->id;
            $modelResultQuestion->weight = $modelQuestion->weight;
            $modelResultQuestion->date_create = new CDbExpression('getdate()');
            $modelResultQuestion->save();

            $forFind = !isset($answers[$modelQuestion->id]) ? [] : $answers[$modelQuestion->id];

            $modelResultQuestion->is_right = $modelResultQuestion->saveAnswers($forFind);
            $modelResultQuestion->save(false, ['is_right']);
            $result['rightAnswers'] += $modelResultQuestion->is_right;
        }

        return $result;
    }
}
