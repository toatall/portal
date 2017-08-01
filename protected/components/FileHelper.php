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
	public static function fileImageDirByNewsId($idNews)
	{		
		$dirImage = str_replace('{code_no}', Yii::app()->session['organization'],
			Yii::app()->params['pathImages']);
		$dirImage = str_replace('{module}', 'news', $dirImage);
		$dirImage = str_replace('{id}', $idNews, $dirImage);
		
		$dirFile = str_replace('{code_no}', Yii::app()->session['organization'],
				Yii::app()->params['pathDocumets']);
		$dirFile = str_replace('{module}', 'news', $dirFile);
		$dirFile = str_replace('{id}', $idNews, $dirFile);
		
		return  ['dirImage'=>$dirImage, 'dirFile'=>$dirFile];
		
	}
	
	
}