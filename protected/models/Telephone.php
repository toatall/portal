<?php

/**
 * This is the model class for table "{{telephone}}".
 *
 * The followings are the available columns in table '{{telephone}}':
 * @property integer $id
 * @property string $ifns_code
 * @property string $telephone_file
 * @property string $author
 * @property string $dop_text
 * @property string $date_create
 * @property integer $sort
 * @property string $actions_log
 * @property int $count_download
 */
class Telephone extends CActiveRecord
{
    
    public $useOptionalAccess = true; // флаг отвечающий за дополнительные настройки прав
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{telephone}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_tree, telephone_file, id_organization, author', 'required'),
			array('id_tree, id, sort, count_download', 'numerical', 'integerOnly'=>true),
			array('id_organization', 'length', 'max'=>5),
			array('telephone_file, author, dop_text', 'length', 'max'=>250),
			array('log_change', 'length', 'max'=>5000),
			array('date_create, count_download', 'unsafe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_tree, id_organization, telephone_file, author, dop_text, date_create, 
                sort, log_change', 'safe', 'on'=>'search'),
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
            'org' => array(self::BELONGS_TO, 'Organization', 'id_organization'),		    
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'УН',
            'id_tree' => 'Раздел',
			'id_organization' => 'Код НО',
			'telephone_file' => 'Файл телефонного справочника',
			'author' => 'Автор',
			'dop_text' => 'Примечание',
			'date_create' => 'Дата создания',
			'sort' => 'Сортировка',
			'log_change' => 'История изменений',
			'count_download' => 'Количество загрузок',
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
	public function search(/*$idTree=null*/)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		
		$criteria->with = array('org');
		
		$criteria->compare('t.id',$this->id);
        $criteria->compare('t.id_tree',$this->id_tree);        
		/*if (Yii::app()->user->admin) { 
			$criteria->compare('id_organization',$this->id_organization,true); 
		}
        else {
        	$criteria->addInCondition('id_organization',
                CHtml::listData($this->listOrganizations($idTree),'code','code'));
       	}*/
		$criteria->compare('t.telephone_file',$this->telephone_file,true);
		$criteria->compare('t.author',$this->author,true);
		$criteria->compare('t.dop_text',$this->dop_text,true);
		$criteria->compare('t.date_create',$this->date_create,true);
		//$criteria->compare('sort',$this->sort);
		$criteria->compare('t.log_change',$this->log_change,true);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'org.code asc'),
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
    
       

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Telephone the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    
	// @todo Нужно ли проверять организации?
    public function listOrganizations($idTree)
    {
        return Organization::model()->findAll();
        
        /*if (Yii::app()->user->admin)
        {
            
        }
        else
        {
            return Yii::app()->db->createCommand("
                SELECT code, name FROM {{organization}} WHERE code IN (
                    SELECT id_organization FROM {{access_telephone}} 
                        WHERE id_tree=$idTree AND ((id_identity=".Yii::app()->user->id." AND is_group=0)
                            OR (id_identity IN (SELECT id_group FROM {{group_user}} 
                                WHERE id_user=".Yii::app()->user->id.") AND is_group=1)))")->queryAll();
        }*/
    }
    
    
    
    public function saveFile($model,$oldFile=null)
    {
        // удаляем старый файл
        if ($oldFile !== null)
        {
            try {
                unlink(Yii::app()->params['pathTelephones'].'/'.$oldFile);
            } catch (exception $e) {}            
        }
        
        // загружаем файл
        $tempFile=CUploadedFile::getInstance($model, 'telephone_file');
        $tempFile->saveAs(Yii::app()->params['pathTelephones'].'/'
            .$model->id_organization.'_'.date('Ymd_His').'.'.pathinfo($tempFile->getName(), PATHINFO_EXTENSION));        
        
    }


    protected function beforeSave()
    {
        if ($this->isNewRecord) 
        {
            $this->date_create = new CDbExpression('getdate()');        
            $this->author = Yii::app()->user->name;
        }
        return parent::beforeSave();
    }
    
    
    protected function afterFind()
    {
    	$this->date_create = ConvertDate::find($this->date_create);    	
        parent::afterFind();
    }
    
    
    /**
     * Загрузка справочника и сохранение в лог
     */
    public function downloadFile()
    {
    	// set log download
    	Yii::app()->db->createCommand()
    		->insert('{{telephone_download}}', [
    			'id_telephone'=>$this->id,
    			'username'=>UserInfo::inst()->userLogin,
    			'ip_address'=>UserInfo::inst()->clientIP,
    			'hostname'=>UserInfo::inst()->clientHost,
    	]);
    	
    	
    	// send file
    	$file = Yii::app()->params["siteRoot"] . '/' . Yii::app()->params["pathTelephones"] . '/' . $this->telephone_file;    
    	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    	header("Content-Type: " . mime_content_type($file));
    	header('Content-Length: '.filesize($file));
    	header("Content-Disposition: inline; filename=\"" . basename($file) . "\"");
    	header('Content-Transfer-Encoding: binary');
    	ob_clean();
    	flush();
    	if (!file_exists($file))
    		return false;
    	readfile($file);
    	Yii::app()->end();
    }
    
    
    
    public function getAccessOrganization()
    {
        if (Yii::app()->user->admin)
        {
            
        }
        else
        {
            
        }
    }
    
    
    
}
