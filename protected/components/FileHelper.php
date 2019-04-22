<?php

/**
 * Хелпер для работы с файлами
 * @author oleg
 */
class FileHelper extends CComponent
{
	
	/**
	 * Вовращает каталоги, где размещаются файлы и изображения
	 * @param int $idNews идентификатор материала
	 * @return array['dirImage', 'dirFile'] 
	 * @uses DepartmentController::showTreeNode()
	 * @uses DepartmentController::showDepartment()
	 */
	public static function fileImageDirByNewsId($idNews, $orgCode=null)
	{		
	    $module = 'news';
	    $model = Yii::app()->db->createCommand()
	       ->from('{{news}}')
	       ->where('id=:id', [':id'=>$idNews])
	       ->query()->read();
	   
	    if ($model !== null)
	    {    	    
    	    $modelTree = Yii::app()->db->createCommand()
    	       ->from('{{tree}}')
    	       ->where('id=:id', [':id'=>$model['id_tree']])
    	       ->query()->read();
    	    
    	    if ($modelTree!==null)
    	        $module = $modelTree['module'];
	    }
	    
	    if ($orgCode==null)
	        $orgCode = Yii::app()->session['organization'];	    
	    
		$dirImage = str_replace('{code_no}', $orgCode,
			Yii::app()->params['pathImages']);
		$dirImage = str_replace('{module}', $module, $dirImage);
		$dirImage = str_replace('{id}', $idNews, $dirImage);
		
		$dirFile = str_replace('{code_no}', $orgCode,
				Yii::app()->params['pathDocumets']);
		$dirFile = str_replace('{module}', $module, $dirFile);
		$dirFile = str_replace('{id}', $idNews, $dirFile);
		
		return  ['dirImage'=>$dirImage, 'dirFile'=>$dirFile];
	}
	
	/**
	 * Сохранение файлов в папку (БД)
	 * 
	 * Если не указан путь файлов, но заполнен массив настроек $modelOptions,
	 * то путь будет сформирован автоматически. Если массив тоже пуст, то запись
	 * в БД будет проигнорирована
	 * 
	 * @param string $name
	 * @param string $pathFile
	 * @param array $modelOptions
	 *     array(
	 *         'name' => 'news', // наименование модели
	 *         'id' => 1, // ИД модели
	 *     )
	 * @return string[]
	 * @uses RatingMain::afterSave()
	 * @uses RatingDataController::actionCreate()
	 */
	public static function filesUpload($name, $pathFile, $modelOptions = array())
	{	    
	    $resultArray = array();
	    
	    $files = CUploadedFile::getInstancesByName($name);
	    	   
	    if (isset($files) && count($files)>0)
	    {
	        
	        if ($pathFile==null && is_array($modelOptions) && !empty($modelOptions['name']) && is_numeric($modelOptions['id']))
	        {
	            $pathFile = Yii::app()->params['pathDocumets'];
	            $pathFile = str_replace('{code_no}', Yii::app()->session['organization'], $pathFile);
	            $pathFile = str_replace('{module}', $modelOptions['name'], $pathFile);
	            $pathFile = str_replace('{id}', $modelOptions['id'], $pathFile);
	        }
	        
	        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $pathFile))
	        {
	            if (!@mkdir($_SERVER['DOCUMENT_ROOT'] . $pathFile, 0777, true))
	            {
	                $resultArray[] = 'Ошибка создания каталога "' . $pathFile . '"';	                
	            }
	        }
	        
	        foreach ($files as $file)
	        {
	            $fileName = iconv('UTF-8', 'windows-1251', $file->name);
	            
	            if ($file->saveAs($_SERVER['DOCUMENT_ROOT'] . $pathFile . $fileName))
	            {
	                if (is_array($modelOptions) && !empty($modelOptions['name']) && is_numeric($modelOptions['id']))
	                {
	                    if (!Yii::app()->db->createCommand()
	                       ->insert('{{file}}', array(
	                           'model'=>$modelOptions['name'],
	                           'id_model'=>$modelOptions['id'],
	                           'file_name'=>$file->name,
	                           'file_size'=>$file->size,
	                           'date_create'=>new CDbExpression('getdate()'),
	                           'id_organization'=>Yii::app()->session['organization'],
	                       )))
	                    {
	                        $resultArray[] = 'Ошибка записи файла "' . $file->name . '" в БД!';	                            
	                    }
	                }
	            }
	            else
	            {
	                $resultArray[] = 'Ошибка сохранения файла "' . $file->name . '"! Ошибка № ' . $file->error;
	            }
	        }
	    }
	    else
	    {
	        // not files
	    }
	    
	    return $resultArray;
	}
	
	/**
	 * Вывод списка уже загруженных файлов, с checkbox, для возможности их удаления
	 * Если список файлов, указанный в массиве $array пуст, то выполняется запрос к БД
	 * для получения файлов (но только в случае указания $modelName и $modelId, в 
	 * противном случае возвращается null)
	 * @param array $files
	 * @param string $modelName
	 * @param string $modelId
	 * @return NULL|mixed
	 * @uses in file 'modules/admin/views/page/_form.php'
	 */
	public static function showFilesUpload($files, $modelName=null, $modelId=null)
	{
	    if (!is_array($files))
	        $files = array($files);
	    
	     // если не удается определить источник файлов, то завершение функции
	     if (count($files)==0 && ($modelName==null || $modelId==null))
	         return null;
	    
	    if (count($files)==0)
	    {
	        $files = CHtml::listData(Yii::app()->db->createCommand()
	           ->from('{{file}}')
	           ->where('model=:model and id_model=:id_model', [
	               ':model'=>$modelName,
	               ':id_model'=>$modelId,
	           ]), 'id', 'file_name');
	    }
	       	  
	    if (count($files))
	    {
            return "<hr />\n" 
            . "<strong>Отметьте файлы для удаления:</strong><br />\n"            
            . CHtml::checkBoxList('deleteFiles[]', '', $files,
                array('labelOptions'=>array('style'=>'display:inline;'), 'style'=>'margin-top:0;')
            );            
	    }
	}
	
	/**
	 * Удаление отмеченных файлов
	 * @param array $options
	 *     [postName] string - имя post-формы, по-умолчанию - deleteFiles
	 *     [modelName] string - имя модели
	 *     [modelId] string - ИД модели
	 *     [all] bool - true/false, в случае необходимости удаления всех файлов (например, в котроллере delete)	     
	 * @uses RatingDataController::actionUpdate()
	 */
	public static function postDeleteFiles($options = array())
	{
	    // имя формы
	    $postName = (!empty($options['postName'])) ? $options['postName'] : 'deleteFiles';
	    
	    $modelName = (!empty($options['modelName']) ? $options['modelName'] : null);
	    $modelId = (!empty($options['modelId']) && is_numeric($options['modelId']) ? $options['modelId'] : null);
	    
	    if ($modelName==null || $modelId==null)
	        return;
	    
	    // если все файлы
	    if (isset($options['all']) && $options['all'])
	    {
	        $modelFiles = Yii::app()->db->createCommand()
	           ->from('{{file}}')
	           ->where('model=:model', ':id_model=:id_model', [':model'=>$modelName, ':model_id'=>$modelId])
	           ->queryAll();
	        $delFile = CHtml::listData($modelFiles, 'id', 'id');
	    }
	    else
	    {
    	    // передаваемые идетификаторы файлов
    	    $delFile = (isset($_POST[$postName]) && count($_POST[$postName])) ? $_POST[$postName] : array();
	    }	    

	    // попытка удалить файлы, помеченные для удаления
	    if (count($delFile) > 0)
	    {	        
	        $dir = str_replace('{code_no}', Yii::app()->session['organization'],
	            Yii::app()->params['pathDocumets']);
	        $dir = str_replace('{module}', $modelName, $dir);
	        $dir = str_replace('{id}', $modelId, $dir);
	        
	        $dir = $_SERVER['DOCUMENT_ROOT'] . $dir;
	        	        
	        foreach ($delFile as $file)
	        {
	            $record = Yii::app()->db->createCommand()
    	            ->from('{{file}}')
    	            ->where('model=:model and id_model=:id_model and id=:id',
    	                array(':model'=>$modelName, ':id_model'=>$modelId, ':id'=>$file))
    	                ->queryRow();
	                
	                if ($record !== null)
	                {
	                    
	                    $fileName = iconv('UTF-8', 'windows-1251', $record['file_name']);
	                    
	                    // удаляем файл
	                    $flagDel=false;
	                    
	                    if (file_exists($dir . $fileName))
	                    {
	                        if (unlink($dir . $fileName))
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
	                    
	                    if ($flagDel)
	                    {
	                        
	                        Yii::app()->db->createCommand()
	                        ->delete(
	                            '{{file}}',
	                            'model=:model and id_model=:id_model and id=:id',
	                            array(':model' => $modelName, ':id_model'=>$modelId, ':id'=>$file
	                            ));
	                    }
	                }
	        }
	    }
	}
	
	/**
	 * Генерация пути за счет имени модели и ИД имодели
	 * @param string $modelName
	 * @param int $modelId
	 * @return mixed
	 * @deprecated
	 */
	public static function generatePathFromModel($modelName, $modelId)
	{
	    $pathFile = Yii::app()->params['pathDocumets'];
	    $pathFile = str_replace('{code_no}', Yii::app()->session['organization'], $pathFile);
	    $pathFile = str_replace('{module}', $modelName, $pathFile);
	    $pathFile = str_replace('{id}', $modelId, $pathFile);
	    return $pathFile;
	}
	
	/**
	 * Расчет размера файла в Байтах, Кб, Мб, Гб, Тб
	 * @param int $size
	 * @return string
	 * @deprecated 
	 */
	public static function fileSizeToText($size)
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
	
	
}