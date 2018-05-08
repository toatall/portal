<?php

/**
 * Управление файлами
 * @author alexeevich
 * @see File
 */
class FileController extends Controller
{

	/**
	 * {@inheritDoc}
	 * @see CController::accessRules()
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('download'),
		 		'users'=>array('@'),
			),
		);
	}
		
	/**
	 * Скачивание файла и сохранение статистической информации
	 * В случае, если файл не найден в БД или в папке,
	 * то возникает ошибка HTTP-404
	 * @param int $id идентификатор файла
	 * @throws CHttpException
	 */
	public function actionDownload($id)
	{
		$model = File::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		$file = Yii::app()->params['siteRoot'] . iconv('utf-8', 'windows-1251', $model->urlFile);
		
		if (!file_exists($file))
			throw new CHttpException(404,'The requested page does not exist.');
		
		// запись информации о скачанном файле
		$model->wtiteLog();
		
		// передача файла пользователю
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header("Content-Type: " . mime_content_type($file));
		header('Content-Length: '.filesize($file));
		header('Content-Disposition: inline; filename="' . basename($model->urlFile) . '"');
		header('Content-Transfer-Encoding: binary');
		ob_clean();
		flush();		
		readfile($file);
		Yii::app()->end();
	}
		
}