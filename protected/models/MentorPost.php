<?php

/**
 * This is the model class for table "{{mentor_post}}".
 *
 * The followings are the available columns in table '{{mentor_post}}':
 * @property integer $id
 * @property integer $id_mentor_ways
 * @property string $id_organization
 * @property string $title
 * @property string $message1
 * @property string $date_create
 * @property string $date_update
 * @property string $date_delete
 * @property string $author
 * @property integer $count_like
 * @property integer $count_comment
 * @property integer $count_visit
 */
class MentorPost extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mentor_post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_mentor_ways, title, message1', 'required'),
			array('id_mentor_ways', 'numerical', 'integerOnly'=>true),
			array('id_organization', 'length', 'max'=>5),
			array('title', 'length', 'max'=>500),
			array('author', 'length', 'max'=>250),			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_mentor_ways, id_organization, title, message1, date_create, date_update, date_delete, author', 'safe', 'on'=>'search'),
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
	        'ways' => array(self::BELONGS_TO, 'MentorWays', 'id_mentor_ways'),
	        'org' => array(self::BELONGS_TO, 'Organization', 'id_organization'),	        
	    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'id_mentor_ways' => 'Направление',
			'id_organization' => 'Организация',
			'title' => 'Заголовок',
			'message1' => 'Текст',
			'date_create' => 'Дата создания',
			'date_update' => 'Дата измненения',
			'date_delete' => 'Дата удаления',
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
		$criteria->compare('id_mentor_ways',$this->id_mentor_ways);
		$criteria->compare('id_organization',$this->id_organization,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('message1',$this->message1,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('date_delete',$this->date_delete,true);
		$criteria->compare('author',$this->author,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
	    $this->date_update = new CDbExpression('getdate()');
	    $this->id_organization = UserInfo::inst()->organizationFromLogin();
	    $this->author = UserInfo::inst()->userLogin;
	    if ($this->isNewRecord)
	    {
	        $this->date_create = new CDbExpression('getdate()');
	    }		    
	    return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterSave()
	 */
	protected function afterSave()
	{
	    $this->saveFiles();
	}
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 */
	protected function afterFind()
	{
	    $this->date_create = DateHelper::explodeDateTime($this->date_create);
	    $this->date_update = DateHelper::explodeDateTime($this->date_update);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MentorPost the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Получение списка файлов
	 * @return array
	 */
	public function getListFiles()
	{
	    $query = Yii::app()->db->createCommand()
	       ->from('p_mentor_post_files')
	       ->where('id_mentor_post=:id_mentor_post', [':id_mentor_post' => $this->id])
	       ->queryAll();
	    return CHtml::listData($query, 'id', 'filename');
	}
	
	/**
	 * Сохранение файлов	
	 * @see MentorPost
	 * @uses MentorController	 
	 */
	private function saveFiles() 
	{	  
	    // сохранение файлов
	    $files = CUploadedFile::getInstancesByName('files');
	    if (isset($files) && count($files) > 0) 
	    {	       
	        // получение каталога для размещения файла
	        $baseDir = str_replace('{code_no}', UserInfo::inst()->organizationFromLogin(),
	            Yii::app()->params['pathDocumets']);
	        $baseDir = str_replace('{module}', 'mentor', $baseDir);
	        $baseDir = str_replace('{id}', $this->id, $baseDir);
	        
	        
	        // создание каталога, если его нет
	        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $baseDir))
	        {
	            mkdir($_SERVER['DOCUMENT_ROOT'] . $baseDir, 0777, true);
	        }
	        
	        // загрузка всех файлов
	        foreach ($files as $file) 
	        {
	            $fileName = iconv('UTF-8', 'windows-1251', $file->name);
	            
	            if ($file->saveAs($_SERVER['DOCUMENT_ROOT'] . $baseDir . $fileName))
	            {
	                Yii::app()->db->createCommand("
                        insert into {{mentor_post_files}} (id_mentor_post, filename, date_create)
                            values ($this->id, '" . $file->name . "',getdate());
                    ")->execute();
	            }
	        }
	    }
	}
	
	/**
	 * Delete files 
	 * @param array|null $files
	 */
	public function deleteFiles($files = null)
	{
	    $query = Yii::app()->db->createCommand()
	       ->from('{{mentor_post_files}}')
	       ->where('id_mentor_post=:id_mentor_post', [':id_mentor_post'=>$this->id]);
	    if ($files)
	    {
	        $query->andWhere(['in', 'id', $files]);	        
	    }
	    
	    foreach ($query->queryAll() as $q)
	    {	        
	        $file = $this->getFullPathFiles() . $q['filename'];	        
	        if (file_exists($file))
            {
               if (@unlink($file))
               {
                   $this->deleteFromDb($q['id']);
               }
            }
            else
            {
                $this->deleteFromDb($q['id']);
            }
	    }	    	   
	}
	
	/**
	 * Get full path for files
	 * @return string
	 */
	private function getFullPathFiles()
	{
	    $path = Yii::app()->params['pathDocumets'];
	    $path = str_replace('{code_no}', $this->id_organization, $path);
	    $path = str_replace('{module}', 'mentor', $path);
	    $path = str_replace('{id}', $this->id, $path);
	    return $_SERVER['DOCUMENT_ROOT'] . $path;
	}
	
	/**
	 * Delete file from db
	 * @param int $id
	 */
	private function deleteFromDb($id)
	{
	    Yii::app()->db->createCommand()->delete('{{mentor_post_files}}', 'id=:id and id_mentor_post=:id_mentor_post', [
	        ':id' => $id,
	        ':id_mentor_post' => $this->id,
	    ]);
	}
	
	
	
}
