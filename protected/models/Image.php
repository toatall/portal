<?php

/**
 * This is the model class for table "{{image}}".
 *
 * The followings are the available columns in table '{{image}}':
 * @property integer $id
 * @property integer $id_page
 * @property string $image_name
 * @property string $image_name_thumbs
 * @property integer $image_size
 * @property string $module
 * @property string $date_create
 */
class Image extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{image}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_page, module, date_create', 'required'),
			array('id_page, image_size', 'numerical', 'integerOnly'=>true),
			array('image_name, image_name_thumbs', 'length', 'max'=>250),
			array('module', 'length', 'max'=>50),
		    // search
			array('id, id_page, image_name, image_name_thumbs, image_size, module, date_create', 'safe', 'on'=>'search'),
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
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_page' => 'Id Page',
			'image_name' => 'Image Name',
			'image_name_thumbs' => 'Image Name Thumbs',
			'image_size' => 'Image Size',
			'module' => 'Module',
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
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_page',$this->id_page);
		$criteria->compare('image_name',$this->image_name,true);
		$criteria->compare('image_name_thumbs',$this->image_name_thumbs,true);
		$criteria->compare('image_size',$this->image_size);
		$criteria->compare('module',$this->module,true);
		$criteria->compare('date_create',$this->date_create,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Image the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * Получение списка файлов
	 * Поиск производится по названию модели ($model_name) 
	 * и ее идентификатору ($model_id)
	 * @param int $model_id идентификатор модели
	 * @param string $model_name наименование модели
	 * @return array
	 * @author alexeevich
	 * @uses DepartmentController::showTreeNode()
	 * @uses DepartmentController::showDepartment()
	 * @uses NewsController::actionView()
	 */
	public static function imagesForDownload($model_id, $model_name)
	{
	    return Yii::app()->db->createCommand()
    	    ->from('{{image}}')
    	    ->where('id_model=:id_model and model=:model', [
    	        ':id_model'=>$model_id,
    	        ':model'=>$model_name,
    	    ])
    	    ->queryAll();
	}
	
}
