<?php

class FileController extends Controller
{

	/**
	 * {@inheritDoc}
	 * @see CController::accessRules()
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
	 * Download file and write log 
	 * @param int $id
	 */
	public function actionDownload($id)
	{
		$model = File::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		$file = Yii::app()->params['siteRoot'] . iconv('utf-8', 'windows-1251', $model->urlFile);
		
		if (!file_exists($file))
			throw new CHttpException(404,'The requested page does not exist.');
		
		// write log
		$model->wtiteLog();
		
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
	
	/*
	private function loadCountLike($id)
	{
		$count = Yii::app()->db->createCommand()
			->select('count(*) count')
			->from('{{like}}')
			->where('id_parent=:id_news',array(':id_news'=>$id))
			->queryScalar();
		
		$countByUser = Yii::app()->db->createCommand()
			->select('count(*) count')
			->from('{{like}}')
			->where('id_parent=:id_news',array(':id_news'=>$id))
			->andWhere('username=:username',array(':username'=>UserInfo::inst()->userLogin))
			->queryScalar();
		
		return '<span class="ic-like'.($countByUser>0 ? '' : '-not').'"></span> Мне нравится <span class="badge" style="background: #3d6899;">'.($count>0 ? $count : '').'</span>';
	}
	*/


}