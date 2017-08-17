<?php

/**
 * This is the model class for table "{{rating_main}}".
 *
 * The followings are the available columns in table '{{rating_main}}':
 * @property integer $id
 * @property integer $id_tree
 * @property string $name
 * @property boolean $order_asc
 * @property string $date_create
 * @property string $log_change
 * @property string $author
 * @property string $note
 *
 * The followings are the available model relations:
 * @property Department $idDepartment
 * @property RatingData[] $ratingDatas
 */
class RatingMain extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{rating_main}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_tree, name', 'required'),
			array('id_tree', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),			
			array('order_asc, note', 'safe'),
			array('id_tree, date_create, log_change, author', 'unsafe'),
			// The following rule is used by search().			
			array('id, id_tree, name, order_asc, date_create, 
				log_change, author, note', 'safe', 'on'=>'search'),
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
			'idDepartment' => array(self::BELONGS_TO, 'Department', 'id_tree'),
			'ratingDatas' => array(self::HAS_MANY, 'RatingData', 'id_tree'),
		    'files' => array(self::HAS_MANY, 'File', 'id_model',
		        'condition'=>"[files].[model]='ratingMain'"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '#',
			'id_tree' => 'Tree.Id',
			'name' => 'Наименование',
			'order_asc' => 'Сортировка по возрастанию',
			'date_create' => 'Дата создания',
			'log_change' => 'Журнал изменения',
			'author' => 'Автор',
			'note' => 'Описание',
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
		$criteria->compare('id_tree',$this->id_tree);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('order_asc',$this->order_asc);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('log_change',$this->log_change,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RatingMain the static model class
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
		$this->author = UserInfo::inst()->userLogin;		
		
		return parent::beforeSave();
	}

	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterSave()
	 */
	protected function afterSave()
	{
	    FileHelper::filesUpload('files', null, ['name'=>'ratingMain', 'id'=>$this->id]);
	    return parent::afterSave();
	}
	
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 */
	protected function afterFind()
	{
		$this->date_create = DateHelper::explodeDateTime($this->date_create);
		return parent::afterFind();
	}
	
}
