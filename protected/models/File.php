<?php

/**
 * This is the model class for table "{{file}}".
 *
 * The followings are the available columns in table '{{file}}':
 * @property integer $id
 * @property integer $id_model
 * @property string $model
 * @property string $file_name
 * @property integer $file_size
 * @property string $date_create
 * @property int $count_download
 * @property string $id_organization
 * @property string $full_filename
 * 
 * @property string urlFile
 */
class File extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{file}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{		
		return array(
			array('id_model, file_name', 'required'),
			array('id_model, file_size, count_download', 'numerical', 'integerOnly'=>true),
			array('file_name', 'length', 'max'=>250),
			array('model', 'length', 'max'=>50),
		    array('id_organization', 'length', 'max'=>5),
		    array('full_filename', 'length', 'max'=>500),
			array('id, count_download, id_organization, full_filename', 'unsafe'),			
			array('id, id_model, file_name, file_size, model, date_create', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 * @deprecated
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
	 * @deprecated
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_model' => 'Id Model',
			'model' => 'Model',
			'file_name' => 'File Name',
			'file_size' => 'File Size',			
			'date_create' => 'Date Create',
			'count_download' => 'Count Download',
		    'id_organization' => 'Organization code',
		    'full_filename' => 'Full filename path',
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
	 * @deprecated
	 */
	public function search()
	{		
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_model',$this->id_model);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('file_size',$this->file_size);		
		$criteria->compare('date_create',$this->date_create,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return File the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::beforeDelete()
	 */
	public function beforeDelete()
	{
		$this->deleteFile();
		return parent::beforeDelete();
	}
	
	
	/**
	 * Удаление текущего файла ($this)
	 * @uses beforeDelete()
	 */
	private function deleteFile()
	{
		$path = Yii::app()->params['pathDocumets'];
		$path = str_replace('{code_no}', $this->id_organization, $path);
		$path = str_replace('{module}', $this->model, $path);
		$path = str_replace('{id}', $this->id_model, $path);
		$filePath = $_SERVER['DOCUMENT_ROOT'] . $path . iconv('UTF-8', 'windows-1251', $this->file_name);
				
		if (file_exists($filePath))
		{
			if (@unlink($filePath))
			{
				return true;
			}
		}
		else			
		{
			return null;
		}		
		return false;
	}
	
	/**
	 * url к файлу
	 * @return string
	 * @deprecated
	 */
	public function getUrlFile()
	{
		$url = Yii::app()->params['pathDocumets'];
		$url = str_replace('{code_no}', $this->id_organization, $url);
		$url = str_replace('{module}', $this->model, $url);
		$url = str_replace('{id}', $this->id_model, $url);
		return $url . $this->file_name;
	}
	
	/**
	 * Запись информации о пользователе, скачивающий файл
	 * @see CDbCommand
	 * @uses FileController::actionDownload()	 
	 */
	public function wtiteLog()
	{
		Yii::app()->db->createCommand('exec {{pr_file_download}} @id_file=:id_file, @username=:username, @session_id=:session_id')
			->bindValue(':id_file', $this->id)
			->bindValue(':username', UserInfo::inst()->userLogin)
			->bindValue(':session_id', session_id())
			->execute();
	}
	
	/**
	 * Получение списка файлов 
	 * Поиск файлаов производится по наименованию модели ($model_name)
	 * и ее идентификатору ($model_id)
	 * @param int $model_id идентификатор модели
	 * @param string $model_name наименование модели
	 * @return array
	 * @author oleg
	 * @uses DepartmentController::showTreeNode()
	 * @uses DepartmentController::showDepartment()
	 * @uses NewsController::actionView()
	 */
	public static function filesForDownload($model_id, $model_name)
	{
	    return Yii::app()->db->createCommand()
	       ->from('{{file}}')
	       ->where('id_model=:id_model and model=:model', [
	           ':id_model'=>$model_id,
	           ':model'=>$model_name,
	       ])
	       ->queryAll();	    
	}
	
}
