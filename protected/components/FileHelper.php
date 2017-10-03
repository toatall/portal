<?php

/**
 * Помощник для работы с файлами
 * @author tvog17
 * @version 05.07.2017
 *
 */
class FileHelper extends CComponent
{
	
	/**
	 * Каталоги, где размещаются файлы и изображения
	 * @param int $idNews
	 * @return array
	 * @version 05.07.2017
	 */
	public static function fileImageDirByNewsId($idNews, $orgCode=null)
	{		
	    $module = 'news';
	    $model = News::model()->findByPk($idNews);
	    if ($model !== null)
	    {
    	    $modelTree = Tree::model()->findByPk($model->id_tree);
    	    if ($modelTree!==null)
    	        $module = $modelTree->module;
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
	                continue;
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
	 * 
	 * Если список файлов, указанный в массиве $array пуст, то выполняется запрос к БД
	 * для получения файлов (но только в случае указания $modelName и $modelId, в 
	 * противном случае возвращается null)
	 * 	 
	 * 
	 * @param array $files
	 * @param string $modelName
	 * @param string $modelId
	 * @return NULL|mixed
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
	 *     
	 *     [all] bool - true/false, в случае необходимости удаления всех файлов (например, в котроллере delete)	     
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
	 */
	public static function generatePathFromModel($modelName, $modelId)
	{
	    $pathFile = Yii::app()->params['pathDocumets'];
	    $pathFile = str_replace('{code_no}', Yii::app()->session['organization'], $pathFile);
	    $pathFile = str_replace('{module}', $modelName, $pathFile);
	    $pathFile = str_replace('{id}', $modelId, $pathFile);
	    return $pathFile;
	}
	
	
}