<?php

/**
 * This is the model class for table "{{news}}".
 *
 * The followings are the available columns in table '{{news}}':
 * @property integer $id
 * @property integer $id_tree
 * @property string $title
 * @property string $message1
 * @property string $message2
 * @property string $author
 * @property string $date_start_pub
 * @property string $date_end_pub
 * @property string $date_create
 * @property string $date_edit
 * @property boolean $date_delete
 * @property boolean $flag_enable
 * @property integer $id_organization
 * @property integer $count_like
 * @property integer $count_comment
 * @property integer $count_visit
 * @property string 
 *
 * The followings are the available model relations:
 * @property Organization $idOrganization
 * @property Section $idSection
 */
class News extends CActiveRecord
{
	
	/**
	 * Имя модуля по умолчанию
	 * @var string
	 */
	const DEFAULT_MODULE = 'page';	
    
	/**
	 * Размер миниатюр для галереи (ширина - пикселей)
	 * @var integer
	 */
    private $_thumbImageHeight = 200;
    
    /**
     * Размер миниатюры для главной страницы
     * @var integer
     */
    private $_miniatureImageHeight = 150; 
    
    /**
     * Флаг отвечающий за дополнительные настройки прав
     * @var bool
     */
    public $useOptionalAccess = false;
    
    /**
     * Миниатюра
     * @var string
     */
    public $_thumbail_image;
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{news}}';
	}         
    
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__)
	{
	    return parent::model($className);
	}
    
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{		
		return array(
			array('id_tree, title, date_start_pub, date_end_pub, message2', 'required'),
			array('id_tree, flag_enable, general_page, id_organization, on_general_page, count_like, count_comment, count_visit', 
				'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>500),
			array('author', 'length', 'max'=>250),
            array('thumbail_image, thumbail_title', 'length', 'max'=>250),
            array('thumbail_text', 'length', 'max'=>1000),
			array('message1, message2, date_create, date_edit, date_delete, 
                flag_enable, general_page, _thumbail_image', 'safe'),
			array('id_tree, id_organization, count_like, count_comment, count_visit', 'unsafe'),
			// search	
			array('id, id_tree, id_organization, title, message1, message2, author, date_start_pub, date_end_pub, date_create,
                date_edit, date_delete, flag_enable, thumbail_image, general_page, param1', 'safe', 'on'=>'search'),
            array('_thumbail_image', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true),
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
			'tree' => array(self::BELONGS_TO, 'Tree', 'id_tree'),
            'organization' => array(self::HAS_ONE, 'Organization', array('code'=>'id_organization')),
            'files' => array(self::HAS_MANY, 'File', 'id_model', 
            	'condition'=>"[files].[model]='news'"),
            'images' => array(self::HAS_MANY, 'Image', 'id_model',
            	'condition'=>"[images].[model]='news'"),
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
			'id_organization' => 'Налоговый орган',
			'title' => 'Заголовок',
			'message1' => 'Кратко',
			'message2' => 'Основной текст',
			'author' => 'Автор',
			'date_start_pub' => 'Начало публикации',
			'date_end_pub' => 'Окончание публикации',
			'date_create' => 'Дата создания',			
			'date_delete' => 'Удален',
			'flag_enable' => 'Опубликовано',
            'thumbail_image' => 'Миниатюра',            
            'general_page' => 'Главная страница',
            'log_change' => 'История изменений',
			'files' => 'Файлы',
			'images' => 'Изображения',
			'on_general_page' => 'Новость дня',
			'count_like' => 'Количество лайков',
			'count_comment' => 'Количество комментариев',
			'count_visit' => 'Количество просмотров',
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
            $this->id_organization = Yii::app()->session['organization'];
        }
        
        if ($this->date_delete == null)
        	$date_delete = new CDbExpression('null');
        
        if ($this->general_page)
        {
            News::model()->updateAll(
                array('general_page'=>0),
                	'id_tree=:id_tree AND id<>:id',
                array(
                    ':id_tree'=>$this->id_tree,
                    ':id'=>$this->id,
                )
            );            
        }               
        return parent::beforeSave();
    }
    
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 */
    protected function afterFind()
    {            
        parent::afterFind();
        $this->date_create      = DateHelper::explodeDateTime($this->date_create);
        $this->date_edit        = DateHelper::explodeDateTime($this->date_edit);
        $this->date_delete      = DateHelper::explodeDateTime($this->date_delete);
        $this->date_start_pub   = DateHelper::explodeDateTime($this->date_start_pub, true);
        $this->date_end_pub     = DateHelper::explodeDateTime($this->date_end_pub, true);        
    }
    
    /**
     * Удаление файлов и изображений из новости 
     * @param integer $id идентификатор новости
     * @param array $delFile массив файлов
     * @param array $delImage массив изображений
     * @param integer $idTree идентификатор структуры
     * @uses DepartmentDataController::actionUpdate()(admin)
     * @uses DepartmentDataController::actionDelete() (admin)
     * @uses NewsController::actionUpdate() (admin)
     * @uses NewsController::actionDelete() (admin)
     * @uses PageController::actionUpdate() (admin)
     * @uses PageController::actionDelete() (admin)
     */
    public function deleteFilesImages($id, $delFile, $delImage, $idTree)
    {    	
        // удаление файлов
        if (count($delFile) > 0) 
        {           
            $dir = str_replace('{code_no}', Yii::app()->session['organization'],
                Yii::app()->params['pathDocumets']);
            $dir = str_replace('{module}', 'news', $dir);
            $dir = str_replace('{id}', $id, $dir);
			
            $dir = $_SERVER['DOCUMENT_ROOT'] . $dir;
            
            foreach ($delFile as $file) 
            {                
            	$record = Yii::app()->db->createCommand()
	            	->from('{{file}}')
	            	->where('model=:model and id_model=:id_model and id=:id', 
	            		array(':model'=>'news', ':id_model'=>$id, ':id'=>$file))
	            	->queryRow();
            	
            	if ($record !== null)
            	{            		            		
            		$fileName = iconv('UTF-8', 'windows-1251', $record['file_name']);            		
                    // удаляем файл
                    $flagDel=false;
                    if (file_exists($dir . $fileName))
                    {                    
                        if (@unlink($dir . $fileName)) 
                        {                           
                        	$flagDel=true;                        	
                        	// если каталог пустой, то удаляем его
                        	if (!count(glob($dir . '*'))) 
                        		@rmdir($dir);
                        }                       
                    }
                    else 
                    {
                        $flagDel=true;
                    }
                    
                    // если удаление файла из каталога прошло успешно
                    if ($flagDel) 
                    {
                    	
                        Yii::app()->db->createCommand()
                            ->delete(
                            	'{{file}}',
                            	'model=:model and id_model=:id_model and id=:id',
                            	array(':model' => 'news', ':id_model'=>$id, ':id'=>$file                            		
                        ));
                    }
                }
            }
        }
        
        // удаление изображений
        if (count($delImage) > 0) 
        {
            
        	$dir = Yii::app()->params['pathImages'];
        	$dir = str_replace('{code_no}', Yii::app()->session['organization'], $dir);
        	$dir = str_replace('{module}', 'news', $dir);
        	$dir = str_replace('{id}', $id, $dir);
        	
        	$dir = $_SERVER['DOCUMENT_ROOT'] . $dir;
        	
            foreach ($delImage as $file)
            {
            	$record = Yii::app()->db->createCommand()
            		->from('{{image}}')
            		->where('model=:model and id_model=:id_model and id=:id',
            				array(':model'=>'news', ':id_model'=>$id, ':id'=>$file))            		
	                ->queryRow();
            		              
                if ($record !== null) 
                {
                    $flagDel=false;					                                       
                    $fileName = iconv('UTF-8', 'windows-1251', $record['image_name']);
                    $fileNameThumb = iconv('UTF-8', 'windows-1251', $record['image_name_thumbs']);
                    
                    if (file_exists($dir . $fileName))
                    {
                        if (@unlink($dir . $fileName)) 
                        {
                            if ($fileName!=$fileNameThumb)
                            	@unlink($dir . $fileNameThumb);
                                                                
                            $flagDel=true;
                            
                            // если каталог пустой, то удаляем его
                            if (!count(glob($dir . '*')))
                        	@rmdir($dir);
                    	}                       
                    } 
                    else 
                    {
                        $flagDel=true;                        
                    }
                    
                    // если удаление файла из каталога прошло успешно
                    if ($flagDel) 
                    {                              	
                        Yii::app()->db->createCommand()->delete(
                        	'{{image}}',
                        	'model=:model and id_model=:id_model and id=:id',
                        	array(':model'=>'news', ':id_model'=>$id, ':id'=>$file));
                        
                    }
                }
            }
        }
    }

    /**
     * Удаление каталога с содержимым
     * @param string $dir
     * @deprecated
     * @todo move to FileHelper
     */
    private function removeDirectory($dir) 
    {
    	$dir = $_SERVER['DOCUMENT_ROOT'] . $dir;
    	
    	if (!file_exists($dir)) return;
    	
    	if ($objs = glob($dir."/*")) 
    	{
    		foreach($objs as $obj) 
    		{
    			try 
    			{
    				is_dir($obj) ? removeDirectory($obj) : @unlink($obj);
    			}
    			catch (exception $e) { /* exception */ }
    		}
    	}
    	
    	try
    	{
    		rmdir($dir);
    	}
    	catch (exception $e) { /* exception */ }
    }
        
    /**
     * Сохранение файлов для новости
     * @param int $id идентификатор новости
     * @param int $idTree идентификатор структуры
     * @see Tree
     * @uses NewsController::actionCreate()
     * @uses DepartmentDataController::actionCreate() (admin)
     * @uses DepartmentDataController::actionUpdate() (admin)
     * @uses NewsController::actionCreate() (admin)
     * @uses NewsController::actionUpdate() (admin)
     * @uses PageController::actionCreate() (admin)
     * @uses PageController::actionUpdate() (admin)
     * @todo Если используется только как вызов от текущей модели, то убрать параметры, и использовать их от $this
     */
    public function saveFiles($id, $idTree)
    {
        $module_name = Tree::model()->findByPk($idTree)->module;

        // сохранение файлов
        $files = CUploadedFile::getInstancesByName('files');
        if (isset($files) && count($files)>0)
        {
            // получение каталога для размещения файла
            $baseDir = str_replace('{code_no}', Yii::app()->session['organization'],
                Yii::app()->params['pathDocumets']);
            $baseDir = str_replace('{module}', $module_name, $baseDir);
            $baseDir = str_replace('{id}', $id, $baseDir);    
            
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
                    Yii::app()->db->createCommand("
                        INSERT INTO {{file}} (model, id_model, file_name, file_size, date_create)
                            VALUES ('news', $id, '" . $file->name . "'," . $file->size . ",getdate());
                    ")->execute();
            }
        }
    }
    
    /**
     * Сохранение изображений для новости
     * @param int $id идентификатор новости
     * @param int $idTree идентификатор структуры
     * @see Tree
     * @see ImageHelper
     * @uses DepartmentDataController::actionCreate() (admin)
     * @uses DepartmentDataController::actionUpdate() (admin)
     * @uses NewsController::actionCreate() (admin)
     * @uses NewsController::actionUpdate() (admin)
     * @uses PageController::actionCreate() (admin)
     * @uses PageController::actionUpdate() (admin)
     * @todo Если используется только как вызов от текущей модели, то убрать параметры, и использовать их от $this
     */
    public function saveImages($id, $idTree)
    {
        // текущий модуль (нужен для указания пути)
        $module_name = Tree::model()->findByPk($idTree)->module;
        
        // сохранение изображений
        $files = CUploadedFile::getInstancesByName('images');
        if (isset($files) && count($files)>0)
        {            
            // получение каталога для размещения изображений
            $baseDir = str_replace('{code_no}', Yii::app()->session['organization'],
                Yii::app()->params['pathImages']);
            $baseDir = str_replace('{module}', $module_name, $baseDir);
            $baseDir = str_replace('{id}', $id, $baseDir);
                        
            // создание каталога, если его нет
            if (!file_exists($_SERVER['DOCUMENT_ROOT'].$baseDir)) 
            {
                mkdir($_SERVER['DOCUMENT_ROOT'] . $baseDir, 0777, true);                
            }
            
            // загрузка всех изображений
            foreach ($files as $file)
            {
                // в случае, если имя кириллицей, то преобразуем к win-1251, чтобы не получились иероглифы
				$fileName = iconv('UTF-8', 'windows-1251', $file->name);
				
                if ($file->saveAs($_SERVER['DOCUMENT_ROOT'] . $baseDir . $fileName)) 
                {
                    // ImageHelper нужен для сохранения копии изображения с меньшими размерами,
                    // что позволит загрузить изображения более быстрее
                    // Оригинал всегда будет доступен при нажатии на маленькое избражение
                    $imageHelper = new ImageHelper;
                    $thumbNameImage = '';
                    if ($imageHelper->load($_SERVER['DOCUMENT_ROOT'] . $baseDir . $fileName))
                    {
                        if ($imageHelper->getHeight() > $this->_thumbImageHeight)
                        {
                            $imageHelper->resizeToHeight($this->_thumbImageHeight);
                            $imageHelper->save($_SERVER['DOCUMENT_ROOT'] . $baseDir . 'thumb_' . $fileName);
                            $thumbNameImage = 'thumb_'.$file->name;
                        }
                        else 
                        {
                            $thumbNameImage = $file->name;
                        }
                    }
                    else
                    {
                        $thumbNameImage = $file->name;
                    }
                    
                    // сохраняем в базу                   
                    Yii::app()->db->createCommand("
                        INSERT INTO {{image}} (model,id_model,image_name,image_name_thumbs,image_size,date_create)
                            VALUES ('news', $id, '" . $file->name."','".$baseDir.$thumbNameImage."',".$file->size.",getdate());
                    ")->execute();
                }
            }
        }
    }
        
    /**
     * Сохранение миниатюры изображения для новости
     * @param News $model новость
     * @param string $oldImageName
     * @see ImageHelper
     * @see Tree
     * @uses NewsController::actionCreate() (admin)
     * @uses NewsController::actionUpdate() (admin)
     * @todo Если используется только как вызов от текущей модели, то убрать параметры, и использовать их от $this
     */
    public function saveThumbailForNews($model,$oldImageName='')
    {
        // текущий модуль (нужен для указания пути)
        $module_name = Tree::model()->findByPk($model->id_tree)->module;
        
        // сохранение изображений
        $file = CUploadedFile::getInstance($model,'_thumbail_image');
        
        // если изображение есть, т.е. его выбрал пользователь
        if (isset($file) && count($file)>0)
        {
            // если файл уже был ранее загружен, то удаляем его
            if ($oldImageName!='')
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $oldImageName))
                    unlink($_SERVER['DOCUMENT_ROOT'] . $oldImageName);
            }
            
            // получение каталога для размещения изображений
            $baseDir = str_replace('{code_no}', Yii::app()->session['organization'],
                Yii::app()->params['miniatureImage']);
            $baseDir = str_replace('{module}', $module_name, $baseDir);
            $baseDir = str_replace('{id}', $model->id, $baseDir);
            
            // создание каталога, если его нет
            if (!file_exists($_SERVER['DOCUMENT_ROOT'].$baseDir))
            {
                mkdir($_SERVER['DOCUMENT_ROOT'].$baseDir, 0777, true);
            }
            
            // в случае, если имя кириллицей, то преобразуем к win-1251, чтобы не получились иероглифы
            $fileName = iconv('UTF-8', 'windows-1251', $file->name);
            
            // ImageHelper приведения размера изображения к определенным размерам
            $imageHelper = new ImageHelper;
            $thumbNameImage = '';
            if ($imageHelper->load($file->tempName))
            {
                if ($imageHelper->getHeight() > $this->_miniatureImageHeight)
                    $imageHelper->resizeToHeight($this->_miniatureImageHeight);

                $imageHelper->save($_SERVER['DOCUMENT_ROOT'] . $baseDir. $fileName);
                                                
                Yii::app()->db->createCommand()
                    ->update('{{news}}', array(
                        'thumbail_image'=> $baseDir .  $file->name,
                    ), 'id=:id', array(':id'=>$model->id));
            }                                                           
        }
    }

    /**
     * Расчет размера файла в Байтах, Кб, Мб, Гб, Тб
     * @param int $size
     * @return string
     * @deprecated
     * @todo перенести данную функцию в FileHelper
     */
    public function getSizeText($size)
    {
        $arBytes = array(
            0 => array('unit'=>'Тб', 'value'=>pow(1024,4)),
            1 => array('unit'=>'Гб', 'value'=>pow(1024,3)),
            2 => array('unit'=>'Мб', 'value'=>pow(1024,2)),
            3 => array('unit'=>'Кб', 'value'=>pow(1024,1)),
            4 => array('unit'=>'Байт', 'value'=>1),            
        );
        
        foreach ($arBytes as $arItem) {
            if ($size >= $arItem['value']) {
                $result = $size / $arItem['value'];
                $result = str_replace('.', ',', strval(round($result,2))).' '.$arItem['unit'];
                break;
            }
        }
        return $result;
    }
        
    /**
     * Получение списка изображений со ссылкой на эти файлы
     * Используется в действиях View, Update
     * @param int $id
     * @param int $idTree
     * @param string $getRecords - параметр означающий, что нужно
     * 		передать массив с id и file_name для действия Update 
     * @return void|string[]|string
     * @deprecated
     * @todo перенести в File
     * @uses in file 'modules/admin/views/departmentData/view.php'
     */
    public function listFiles($id, $idTree, $getRecords=false)
    {
    	    	
    	$dir = str_replace('{code_no}', Yii::app()->session['organization'],
    			Yii::app()->params['pathDocumets']);
    	$dir = str_replace('{module}', 'news', $dir);
    	$dir = str_replace('{id}', $this->id, $dir);
    	
    	    	
    	
        $model = Tree::model()->findByPk($idTree);
        if ($model===null) return;
        $module_name = $model->module;
        
        $files = Yii::app()->db->createCommand(array(
            'select'=>'*',
            'from'=>'{{file}}',
            'where'=>'model=:model and id_model=:id_model',
            'params'=>array(':model'=>'news', ':id_model'=>$id),
        ))->queryAll();                
        
        if ($getRecords)
        {
            $res_array = array();
            foreach ($files as $file)
            {
                $res_array[$file['id']]='<i class="icon-file"></i> '.$file['file_name'].' <a href="'
                     . $dir . $file['file_name'] . '" target="_blank">(скачать)</a> ('.$this->getSizeText($file['file_size']).')';                
            }
            return $res_array;
        }
        
        $list = ''; $size = 0;
        foreach ($files as $file)
        {
            
            $list .= '<i class="icon-file"></i> <a href="' . $dir . $file['file_name'] . '" target="_blank">'
                .$file['file_name'].'</a> ('.$this->getSizeText($file['file_size']).')<br />';  
            $size += $file['file_size'];  
        }     
        if ($list!='')
        {
            $list.='<i>Общий размер: '.$this->getSizeText($size).'</i>';
        }
                   
        return ($list!='') ? $list : 'Нет';
    }                    
    
    
       
    /**
     * Получение списка изображений со ссылкой на эти изображения
     * Используется в действиях View, Update
     * @param int $id
     * @param int $idTree
     * @param string @getRecords=true - параметр означающий, что нужно
                   передать массив с id и image_name для действия Update 
     * @return void|string[]|string
     * @author tvog17
     * @deprecated
     * @todo перенести в Image
     * @uses in file 'modules/admin/views/departmentData/view.php'
     */
    public function getListImages($id, $idTree, $getRecords=false)
    {       
    	
    	$dir = str_replace('{code_no}', Yii::app()->session['organization'],
    			Yii::app()->params['pathImages']);
    	$dir = str_replace('{module}', 'news', $dir);
    	$dir = str_replace('{id}', $this->id, $dir);
    	
        $model = Tree::model()->findByPk($idTree);
        if ($model===null) return;
        $module_name = $model->module;
        
        $files = Yii::app()->db->createCommand(array(
            'select'=>'*',
            'from'=>'{{image}}',
            'where'=>'model=:model and id_model=:id_model',
            'params'=>array(':model'=>'news', ':id_model'=>$id)
        ))->queryAll();                
                
        if ($getRecords) 
        {
            $res_array = array();
            foreach ($files as $file)
            {
                $res_array[$file['id']]='<i class="icon-picture"></i> '.$file['image_name'].' <a class="fancybox" href="'
                    . $dir . $file['image_name'] .'" target="_blank">(просмотр)</a> ('.$this->getSizeText($file['image_size']).')';                
            }
            return $res_array;
        }
        
        $list = ''; $size = 0;
        foreach ($files as $file)
        {
            $list .= '<i class="icon-picture"></i> <a class="fancybox" href="'. $dir . $file['image_name'] .'" target="_blank">'
                .$file['image_name'].'</a> ('.$this->getSizeText($file['image_size']).')<br />'; 
            $size += $file['image_size'];     
        }      
        if ($list!='')
        {
            $list.='<i>Общий размер: '.$this->getSizeText($size).'</i>';
        }
        
        return ($list!='')?$list:'Нет';
    }
    
    /**
     * Get organization name
     * @return string
     * @author tvog17
     * @deprecated
     * @todo move to Organization
     */
    public function getOrganization()
    {        
        return Organization::model()->find(array('condition'=>array(
            'code'=>$this->tree->id_organization,
        )))->name;
    }
        
    /**
     * Миниатюра (с каталогом)
     * @return string
     */
    public function getThumbail_image_path()
    {  
    	$dir = str_replace('{code_no}', Yii::app()->session['organization'],
    		Yii::app()->params['miniatureImage']);
    	$dir = str_replace('{module}', 'news', $dir);
    	$dir = str_replace('{id}', $this->id, $dir);
    	
    	return $dir . $this->thumbail_image;    	
    }
    
    /**
     * Название организации для текущей новости
     * @return string
     */
    public function getOrganization_name()
    {
        return $this->organization->name;
    }
    
	/** 
	 * @param unknown $treeModel
	 * @deprecated
	 */
	public function treeAction($treeModel)
	{
	    
	}
	
	/**
	 * Журнал изменений (адаптированный для простомтра) 
	 * @return string
	 */
	public function getLogChangeText()
	{
	    return Log::getLog($this->log_change);
	}
	
	
}
