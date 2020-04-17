<?php

/**
 * This is the model class for table "{{zg_template}}".
 *
 * The followings are the available columns in table '{{zg_template}}':
 * @property integer $id
 * @property string $kind
 * @property string $description
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 */
class Template extends CActiveRecord
{
    /**
     * Идентификаторы для удаления файлов
     * @var array
     */
    public $deleteFile;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{zg_template}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kind', 'required'),
			array('kind', 'length', 'max'=>1000),
			array('author', 'length', 'max'=>250),
			array('description, date_update, deleteFile', 'safe'),
			// The following rule is used by search().
			array('id, kind, description, date_create, date_update, author', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'kind' => 'Вид обращений',
			'description' => 'Описание',
			'date_create' => 'Дата создания',
			'date_update' => 'Дата изменения',
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
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('kind',$this->kind,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('author',$this->author,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Template the static model class
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
        if (!parent::beforeSave()) {
            return false;
        }

        if ($this->isNewRecord)
        {
            $this->date_create = new CDbExpression('getdate()');
        }
        $this->date_update = new CDbExpression('getdate()');
        $this->author = Yii::app()->user->name;

        return true;
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
        $this->date_update = $dateHelper->asDateTime($this->date_update);
    }

    protected function beforeDelete()
    {
        if (!parent::beforeDelete())
        {
            return false;
        }
        $this->deleteFiles();
        return true;
    }

    /**
     * Список видов обращений
     * @return array
     * @throws CException
     */
    public function listKinds()
    {
        /** @var $command CDbCommand */
        $command = Yii::app()->db->createCommand();
        $result = $command->from('{{zg_template_kind}}')
                ->order('kind_name')
                ->queryAll();
        return CHtml::listData($result, 'kind_name', 'kind_name');
    }

    /**
     * Сохранение файлов
     */
    public function saveFiles()
    {
        $files = CUploadedFile::getInstancesByName( 'files');
        if (isset($files) && count($files) > 0)
        {
            // получение каталога для размещения файла
            $path = $this->preparePath();

            // перебор всех файлов
            foreach ($files as $file)
            {
                $fileName = $this->prepareFileName($file->name);

                if ($file->saveAs($path . DIRECTORY_SEPARATOR . $fileName))
                {
                    $this->addDbFile($file->name);
                }
            }
        }
    }

    /**
     * Подготовка пути для сохранения файла
     * @return string
     */
    protected function preparePath()
    {
        $path = Yii::app()->params['zg']['template']['pathFiles'];
        $path = str_replace('{id}', $this->id, $path);
        $fullPath = Yii::app()->params['siteRoot'] . $path;
        if (!file_exists($fullPath))
        {
            CFileHelper::createDirectory($fullPath, null, true);
        }
        return $fullPath;
    }

    public function getUrlFile($file = '')
    {
        $path = Yii::app()->params['zg']['template']['pathFiles'];
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
     * Добавление записи в БД
     * @param $filename
     */
    protected function addDbFile($filename)
    {
        Yii::app()->db->createCommand("
            insert into {{zg_template_file}} (id_zg_template, filename, date_create)
                values ($this->id, '{$filename}', getdate());
            ")->execute();
    }

    public function getUploadedFiles()
    {
        /** @var $command CDbCommand */
        $command = Yii::app()->db->createCommand();
        return $command->from('{{zg_template_file}}')
            ->where('id_zg_template=:id_zg_template', [':id_zg_template'=>$this->id])
            ->queryAll();
    }

    public function getListFiles()
    {
        return CHtml::listData($this->getUploadedFiles(), 'id', 'filename');
    }
    
    public function deleteFiles($files = [])
    {
        /** @var $command CDbCommand */
        $command = Yii::app()->db->createCommand();
        $query = $command->from('{{zg_template_file}}')
            ->where('id_zg_template=:id_zg_template', [':id_zg_template'=>$this->id]);
        if ($files)
        {
            $query->andWhere(['in', 'id', $files]);
        }

        foreach ($query->queryAll() as $item)
        {
            $this->deleteFileFromDisk($item['filename']);
            $this->deleteFileFromDb($item['id']);
        }
    }

    private function deleteFileFromDisk($file)
    {
        $file = $this->preparePath() . $this->prepareFileName($file);
        if (file_exists($file))
        {
            unlink($file);
        }
    }

    private function deleteFileFromDb($idFile)
    {
        /** @var $command CDbCommand */
        $command = Yii::app()->db->createCommand();
        $command->delete('{{zg_template_file}}', 'id=:id', [':id'=>$idFile]);
    }


}
