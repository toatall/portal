<?php

class LikeController extends Controller
{

	/**
	 * {@inheritDoc}
	 * @see CController::accessRules()
	 */
	public function accessRules()
	{
		return array(
			 array('allow',
			 		'users'=>array('@'),
			 ),
		);
	}
	
	
	/**
	 * Добавление лайка (или) удаление
	 * @param int $id
	 */
	public function actionAdd($id)
	{		
		Yii::app()->db->createCommand('exec p_pr_like_news @username=:username, @ip_address=:ip_address, @id_parent=:id')
			->bindValue(':username', UserInfo::inst()->userLogin)
			->bindValue(':ip_address', UserInfo::inst()->clientIP)
			->bindValue(':id', $id)
			->execute();		
		
		echo $this->loadCountLike($id);
	}
	
	
	/**
	 * Получение количества лайков
	 * @param int $id
	 */
	public function actionCount($id)
	{
		echo $this->loadCountLike($id);
	}
	
	
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



}