<?php

/**
 * This is the model class for table "{{telephone}}".
 *
 * The followings are the available columns in table '{{telephone}}':
 * @property integer $id
 * @property integer $id_tree
 * @property string $id_organization
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
    /**
     * Флаг отвечающий за дополнительные настройки прав
     * @var bool
     */
    public $useOptionalAccess = true;
    
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
	 * {@inheritDoc}
	 * @see CActiveRecord::beforeSave()
	 */
	protected function beforeSave()
	{
	    if ($this->isNewRecord)
	    {
	        $this->date_create = new CDbExpression('getdate()');
	        $this->author = Yii::app()->user->name;
	    }
	    return parent::beforeSave();
	}
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 */
	protected function afterFind()
	{
	    $this->date_create = DateHelper::explodeDateTime($this->date_create);
	    parent::afterFind();
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
		
		$criteria->with = array('org');
		
		$criteria->compare('t.id',$this->id);
        $criteria->compare('t.id_tree',$this->id_tree);        		
		$criteria->compare('t.telephone_file',$this->telephone_file,true);
		$criteria->compare('t.author',$this->author,true);
		$criteria->compare('t.dop_text',$this->dop_text,true);
		$criteria->compare('t.date_create',$this->date_create,true);		
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
	 * Поиск для адмнистративной зоны
	 * @param int $idTree
	 * @return CActiveDataProvider
	 */
	public function searchAdmin()
	{
	    $criteria=new CDbCriteria;
	    $criteria->with = array('org');
	    $criteria->compare('t.id',$this->id);
        $criteria->compare('t.id_tree',$this->id_tree);
		$criteria->compare('t.telephone_file',$this->telephone_file,true);
		$criteria->compare('t.author',$this->author,true);
		$criteria->compare('t.dop_text',$this->dop_text,true);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.log_change',$this->log_change,true);
		if (!Yii::app()->user->admin)
		{
		    $criteria->addInCondition('id_organization', 
		        CHtml::listData($this->accessOrganization($this->id_tree), 'code', 'code'));   
		}
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'org.code asc'),
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}
    
	/**
	 * Получение списка организаций согласно правам текущего пользователя
	 * Для размещения телефонных справочников
	 * @return array
	 * @author alexeevich	 
	 * @uses searchAdmin()
	 * @uses listOrganizations()
	 */
	private function accessOrganization($idTree)
	{
	    if (Yii::app()->user->admin)
	        return;
	    
	    $query = Yii::app()->db->createCommand();
	    $query->text = "
            select distinct p.code, p.name from
            (
            	select t.id_organization from p_access_organization_group t
            		join p_access_group access_group on t.id_access_group=access_group.id
            		join p_group_user group_user on group_user.id_group=access_group.id_group
            	where access_group.id_tree=:id_tree1 and access_group.id_organization=:id_organization1
            		and group_user.id_user=:id_user1
            	union 
                select t.id_organization from p_access_organization_user t
            		join p_access_user access_user on t.id_access_user=access_user.id	
            	where access_user.id_tree=:id_tree2 and access_user.id_organization=:id_organization2
            		and access_user.id_user=:id_user2
            ) as x
            join p_organization p on x.id_organization=p.code";
	    
	    $query->bindValue(':id_tree1', $idTree);
	    $query->bindValue(':id_tree2', $idTree);
	    $query->bindValue(':id_organization1', Yii::app()->session['organization']);
	    $query->bindValue(':id_organization2', Yii::app()->session['organization']);
	    $query->bindValue(':id_user1', Yii::app()->user->id);
	    $query->bindValue(':id_user2', Yii::app()->user->id);	   
	    
	    return $query->queryAll();
	}
	
	/**
	 * Проверка наличия прав у пользователя на доступ к указанной орагнизации
	 * @param string $organization
	 * @return boolean
	 * @deprecated
	 */
	public function checkAccessOrganization()
	{
	    if (Yii::app()->user->admin)
	        return true;
	    
	    $query = Yii::app()->db->createCommand();
	    $query->text = "
            select distinct x.id_organization from
            (
            	select t.id_organization from p_access_organization_group t
            		join p_access_group access_group on t.id_access_group=access_group.id
            		join p_group_user group_user on group_user.id_group=access_group.id_group
            	where access_group.id_tree=:id_tree1 and access_group.id_organization=:id_organization1
            		and group_user.id_user=:id_user1 and t.id_organization=:id_organization_find1
            	union select t.id_organization from p_access_organization_user t
            		join p_access_user access_user on t.id_access_user=access_user.id
            	where access_user.id_tree=:id_tree2 and access_user.id_organization=:id_organization2
            		and access_user.id_user=:id_user2 and t.id_organization=:id_organization_find2
            ) as x
            ";
	    $query->bindValue(':id_tree1', $this->id_tree);
	    $query->bindValue(':id_tree2', $this->id_tree);
	    $query->bindValue(':id_organization1', Yii::app()->session['organization']);
	    $query->bindValue(':id_organization2', Yii::app()->session['organization']);
	    $query->bindValue(':id_user1', Yii::app()->user->id);
	    $query->bindValue(':id_user2', Yii::app()->user->id);
	    $query->bindValue(':id_organization_find1', $this->id_organization);
	    $query->bindValue(':id_organization_find2', $this->id_organization);
	    
	    $result = $query->queryScalar();
	    
	    if ($result>0)
	        return true;
	           else return false;
	    
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
    
	/**
	 * Список доступных организаций для размещения телефонного справочника
	 * @param int $idTree идентификатор структуры
	 * @return Organization[]
	 */
    public function listOrganizations($idTree)
    {
        if (Yii::app()->user->admin)
        {
            return Organization::model()->findAll();
        }        
        return $this->accessOrganization($idTree);
    }
    
    /**
     * Сохранение файла и удаление старого
     * @param self $model объект текущего класса
     * @param string $oldFile имя старого файла
     */
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
    
    /**
     * Скачивание справочника и сохранение информации в лог
     * @uses SiteController::actionTelephoneDownload()
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
    
    /**
     * @deprecated
     */
    public function getAccessOrganization()
    {
        throw new CHttpException(410);
        /*
        if (Yii::app()->user->admin)
        {
            
        }
        else
        {
            
        }*/
    }
    
    
    
}
