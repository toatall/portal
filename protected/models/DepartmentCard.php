<?php

/**
 * This is the model class for table "{{department_card}}".
 *
 * The followings are the available columns in table '{{department_card}}':
 * @property integer $id
 * @property integer $id_department
 * @property integer $id_user
 * @property string $user_fio
 * @property string $user_rank
 * @property string $user_position
 * @property string $user_telephone
 * @property string $user_photo
 * @property integer $user_level
 * @property integer $sort_index
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 * @property string $user_resp
 *
 * The followings are the available model relations:
 * @property Department $idDepartment
 */
class DepartmentCard extends CActiveRecord
{
	
	public $photoFile;
	
	private $_imageHeight = 400; // размер (ширина) изображения (аватарки)
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{department_card}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_department', 'required'),
			array('user_fio, user_position, user_telephone', 'required'),
			array('id_department, id_user, user_level, sort_index', 'numerical', 'integerOnly'=>true),
			array('user_fio', 'length', 'max'=>500),
			array('user_rank, user_position', 'length', 'max'=>200),
			array('user_telephone', 'length', 'max'=>50),
			array('user_photo', 'length', 'max'=>250),
			array('date_edit, user_resp', 'safe'),
			array('photoFile', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_department, id_user, user_fio, user_rank, user_position, 
					user_telephone, user_photo, user_level, sort_index, date_create, 
					date_edit, log_change, user_resp', 'safe', 'on'=>'search'),
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
			'department' => array(self::BELONGS_TO, 'Department', 'id_department'),
			'user' => array(self::BELONGS_TO, 'User', 'id_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '#',
			'id_department' => '# отдела',
			'id_user' => '# пользователя',
			'user_fio' => 'ФИО',
			'user_rank' => 'Чин',
			'user_position' => 'Должность',
			'user_telephone' => 'Телефон',
			'user_photo' => 'Фотография',
			'user_level' => 'Уровень',
			'sort_index' => 'Индекс сортировки',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
			'log_change' => 'Журнал изменений',
			'photoFile' => 'Фотография',
			'user_resp' => 'Обязанности',
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
	public function search($id_department)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_department',$id_department);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('user_fio',$this->user_fio,true);
		$criteria->compare('user_rank',$this->user_rank,true);
		$criteria->compare('user_position',$this->user_position,true);
		$criteria->compare('user_telephone',$this->user_telephone,true);
		$criteria->compare('user_photo',$this->user_photo,true);
		$criteria->compare('user_level',$this->user_level);
		$criteria->compare('sort_index',$this->sort_index);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('log_change',$this->log_change,true);
		$criteria->compare('user_resp',$this->user_resp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DepartmentCard the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	// получить имя пользователя
	public function getUserName()
	{
		return (isset($this->user->id) ? $this->user->profile->name : $this->user_fio);
	}
	
	public function beforeSave()
	{
		$this->date_create = new CDbExpression('getdate()');
		return parent::beforeSave();
	}
	
	
	/**
	 * Загрузка файла (аватара) сотрудника отдела
	 * в каталог 
	 * @param DepartmentCard $model
	 * @author oleg
	 * @version 01.09.2016
	 */
	public function loadFilePhoto($model)
	{
		
		$file = CUploadedFile::getInstance($model,'photoFile');
		if (isset($file) && count($file)>0)
		{
			
			$path = str_replace('{code_no}', Yii::app()->session['organization'], 
				Yii::app()->params['pathCardImage']);
			
						
			// если каталога еще нет, то создадим его
			if (!file_exists($path))
			{
				if (!@mkdir($path, 0777, true))
					return;
			}
				
			// удалить старый файл
			if ($model->user_photo != null)
			{
				if (file_exists(Yii::app()->params['siteRoot'] . $path . $model->user_photo))
					@unlink(Yii::app()->params['siteRoot'] . $path . $model->user_photo);
			}
			
			
			
			// загрузить новый файл
			$imageHelper = new ImageHelper;
			$thumbNameImage = '';
			if ($imageHelper->load($file->tempName))
			{
				
				$fileName = $this->generateFileName($file->name);
				
				if ($imageHelper->getHeight() > $this->_imageHeight)
					$imageHelper->resizeToHeight($this->_imageHeight);
								
					$imageHelper->save(Yii::app()->params['siteRoot'] . $path . $fileName);
				
					Yii::app()->db->createCommand()
						->update($this->tableName(),[
							'user_photo' => $path . $fileName,
						], 'id=:id', [':id'=>$this->id]);							
			}
			
		}		
			
	}
	
	
	private function generateFileName($filename)
	{
		$path_info = pathinfo($filename);
		return date('Ymd_hns.') . $path_info['extension'];
	}
	
	
	// @todo !!! что я здесь проверял??
	public function getStruct()
	{
		echo 1; exit;
		$criteria = new CDbCriteria();
		$criteria->compare('id_department', $this->id_department);
		
		$model = self::model()->findAll($criteria);
		print_r($model);exit;
		
		$resultArray = array();		
		
		foreach ($model as $m)
		{
			$resultArray[$m->user_level] = [
				'photo' => $m->user_photo,
				'user_fio' => $m->user_fio,
				'user_rank' => $m->user_rank,
				'user_position' => $m->user_position,
				'user_telephone' => $m->user_telephone,					
			];
		}
		
		return $resultArray;
		
	}
	
	
	
}
