<?php

class CommentController extends Controller
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
	 * Удаление коментария
	 * @param int $id
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		if (Yii::app()->user->inRole('admin') || $model->username == UserInfo::inst()->userLogin)
		{
			$model->date_delete = new CDbExpression('getdate()');
			if ($model->save())
			{
				echo 'OK';
				Yii::app()->end();
			}
			echo 'Ошибка при сохранении модели!';
		}
		else
		{
			throw new CHttpException(403,'Доступ ограничен!');
		}
	}
	
	
	public function actionForm($id)
	{
		// для проверки наличия новости
		$modelAll = $this->loadModels($id);
		
		$model = new Comment();
		$model->id_parent = $id;
		$model->username = UserInfo::inst()->userLogin;
		$model->ip_address = UserInfo::inst()->clientIP;
		$model->hostname = UserInfo::inst()->clientHost;
				
		if(isset($_POST['Comment']))
		{			
			$model->attributes=$_POST['Comment'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success', 'Ваш комментарий успешно сохранен!');
				$model->comment = '';
			}
		}
		
		return $this->renderPartial('_form',array(
			'id'=>$id,
			'model'=>$model,
			'modelAll'=>$modelAll,
		));
	}
	
	
	public function actionIndex($id)
	{		
		return $this->renderPartial('comments',array(	
			'id'=>$id,
			'model'=>$this->loadModels($id),
		));
	}
	
	
	
	private function loadModel($id)
	{
		$model = Comment::model()->findByPk($id);
		if ($model===null)
			throw new CHttpException(404,'Страница не найдена!');
		return $model;
	}
	
	
	private function loadModels($id)
	{
		$model = Comment::model()->findAll(array('condition'=>'id_parent=:id_parent','params'=>array(':id_parent'=>$id),'order'=>'date_create asc'));
		if ($model===null)
			throw new CHttpException(404,'Страница не найдена!');
		return $model;
	}



}