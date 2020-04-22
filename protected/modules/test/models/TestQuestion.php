<?php

/**
 * This is the model class for table "{{test_question}}".
 *
 * The followings are the available columns in table '{{test_question}}':
 * @property integer $id
 * @property integer $id_test
 * @property string $name
 * @property integer $type_question
 * @property string $attach_file
 * @property integer $weight
 * @property string $date_create
 * @property string $author
 *
 * The followings are the available model relations:
 * @property Test $test
 * @property TestAnswer[] $testAnswers
 * @property TestResultQuestion[] $testResultQuestions
 */
class TestQuestion extends CActiveRecord
{
    const TYPE_QUESTION_RADIO = 0;
    const TYPE_QUESTION_CHECK = 1;

    /**
     * Поле для передачи файла
     * @var string
     */
    public $file;

    /**
     * Пометка о необходимости удаления файла
     * @var bool
     */
    public $delFile = false;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{test_question}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type_question, weight', 'required'),
			array('id_test, type_question, weight', 'numerical', 'integerOnly'=>true),
			array('name, author', 'length', 'max'=>250),
			array('attach_file', 'length', 'max'=>200),
			array('file', 'file', 'allowEmpty' => true),
            array('delFile', 'boolean'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_test, name, type_question, attach_file, weight, date_create, author', 'safe', 'on'=>'search'),
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
			'test' => array(self::BELONGS_TO, 'Test', 'id_test'),
			'testAnswers' => array(self::HAS_MANY, 'TestAnswer', 'id_test_question'),
			'testResultQuestions' => array(self::HAS_MANY, 'TestResultQuestion', 'id_test_question'),
            'userModel' => array(self::BELONGS_TO, 'User', ['author' => 'username_windows']),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'id_test' => 'Тест',
			'name' => 'Наименование',
			'type_question' => 'Тип вопроса',
			'attach_file' => 'Файл',
			'weight' => 'Количество баллов',
			'date_create' => 'Дата создания',
			'author' => 'Автор',
            'delFile' => 'Удалить файл',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type_question',$this->type_question);
		$criteria->compare('attach_file',$this->attach_file,true);
		$criteria->compare('weight',$this->weight);
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
	 * @return TestQuestion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Тип варианта ответов
     * @return array
     */
	public function getTypes()
    {
        return [
            self::TYPE_QUESTION_RADIO => 'С выбором одного ответа',
            self::TYPE_QUESTION_CHECK => 'С выбором нескольких ответов',
        ];
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
    }

    /**
     * @inheritDoc
     * @return  bool
     */
    protected function beforeDelete()
    {
        $this->deleteFromDisk();
        return parent::beforeDelete();
    }


    /**
     * Сохранение файлов
     */
    public function saveFile()
    {
        if ($this->delFile)
        {
            $this->deleteFile();
        }

        $file = CUploadedFile::getInstance($this, 'file');
        if (isset($file))
        {
            // получение каталога для размещения файла
            $path = $this->preparePath();

            $fileName = $this->prepareFileName($file->name);
            if ($file->saveAs($path . DIRECTORY_SEPARATOR . $fileName))
            {
                $this->updateAttachInDb($file->name);
            }
        }
    }

    /**
     * Подготовка пути для сохранения файла
     * @return string
     */
    protected function preparePath()
    {
        $path = Yii::app()->params['test']['question']['pathFiles'];
        $path = str_replace('{id}', $this->id, $path);
        $fullPath = Yii::app()->params['siteRoot'] . $path;
        if (!file_exists($fullPath))
        {
            CFileHelper::createDirectory($fullPath, null, true);
        }
        return $fullPath;
    }

    /**
     * Подготовка ссылки на файл
     * @param string $file
     * @return string
     */
    public function getUrlFile($file = '')
    {
        $path = Yii::app()->params['test']['question']['pathFiles'];
        $path = str_replace('{id}', $this->id, $path);
        return $path . $file;
    }

    /**
     * Подготовка имени файла
     * @param $filename
     * @return false|string
     */
    protected function prepareFileName($filename)
    {
        return iconv('UTF-8', 'windows-1251', $filename);
    }

    /**
     * Обновление информации о файле в БД
     * @param $filename
     * @return bool
     */
    private function updateAttachInDb($filename = null)
    {
        $this->attach_file = $filename;
        return $this->save(false, ['attach_file']);
    }

    /**
     * Удаление файла и информации о нем в таблице
     */
    private function deleteFile()
    {
        $this->deleteFromDisk();
        $this->updateAttachInDb();
    }

    /**
     * Удаление файла из папки
     */
    private function deleteFromDisk()
    {
        $file = $this->preparePath() . $this->prepareFileName($this->attach_file);
        if (file_exists($file) && is_file($file))
        {
            unlink($file);
        }
    }

    /**
     * Ссылка на файл
     * @return string
     */
    public function getAttachUrl()
    {
        return $this->getUrlFile($this->attach_file);
    }


}
