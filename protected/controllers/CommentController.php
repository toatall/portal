<?php

/**
 * Комментарии к материалам 
 * @author alexeevich
 * @see Comment
 */
class CommentController extends Controller
{

	/**
	 * Установка прав доступа к действиям
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
	 * Удаление коментария по идентификатору  
	 * В действительности происходит установка признака (дата удаления), что комментарий удален
	 * В случае успешного сохранения возвращается текст "OK" или текст с ошибкой
	 * Доступ предоставляется только для автора и администратора
	 * @param int $id
	 * @return string|CHttpException
	 * @see Comment	
	 * @see UserInfo
	 * @see CDbExpression
	 * @throws CHttpException
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
	
	/**
	 * Delete comment mentor
	 * @param int $id
	 * @throws CHttpException
	 */
	public function actionDeleteMentor($id)
	{
	    $model = $this->loadModelMentor($id);
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

    /**
     * Форма для добавления комментария
     * @param int $id
     * @return string
     * @throws CException
     * @throws CHttpException
     * @see Comment
     * @see UserInfo
     */
	public function actionForm($id)
	{
		// для проверки наличия новости
		$modelAll = $this->loadModels($id);
		
		$model = new Comment();
		$model->id_parent = $id;
		$model->username = UserInfo::inst()->userLogin;
		$model->ip_address = UserInfo::inst()->clientIP;
		$model->hostname = UserInfo::inst()->clientHost;
				
		if (isset($_POST['Comment']))
		{			
			$model->attributes = $_POST['Comment'];
			if ($model->save())
			{
				echo 'OK';
				Yii::app()->end();
			}
		}
		
		return $this->renderPartial('_form', [
			'id'=>$id,
			'model'=>$model,
			'modelAll'=>$modelAll,
		]);
	}


    /**
     * Форма для добавления комментария
     * @param int $id
     * @return string
     * @throws CException
     * @throws CHttpException
     * @see Comment
     * @see UserInfo
     */
	public function actionFormMentor($id)
	{
	    // для проверки наличия новости
	    $modelAll = $this->loadModelsMentor($id);
	    
	    $model = new MentorComment();
	    $model->id_mentor_post = $id;
	    $model->username = UserInfo::inst()->userLogin;
	    $model->ip_address = UserInfo::inst()->clientIP;
	    $model->hostname = UserInfo::inst()->clientHost;
	    
	    if(isset($_POST['MentorComment']))
	    {
	        $model->attributes=$_POST['MentorComment'];
	        if($model->save())
	        {
	            echo 'OK';
	            Yii::app()->end();
	        }
	    }
	    
	    return $this->renderPartial('_form', [
	        'id'=>$id,
	        'model'=>$model,
	        'modelAll'=>$modelAll,
	        'urlForm' => Yii::app()->controller->createUrl('comment/formMentor',array('id'=>$id)),
	        'urlUpdate' => Yii::app()->controller->createUrl('comment/updateMentor',array('id'=>$model->id)),
	    ]);
	}


    /**
     * Отображение комментариев,
     * которые привязаны к странице с идетификатором $id
     * @param int $id
     * @return string
     * @throws CException
     * @throws CHttpException
     */
	public function actionComments($id)
	{		
		return $this->renderPartial('comments',array(	
			'id'=>$id,
			'model'=>$this->loadModels($id),
		));
	}


    /**
     * Отображение комментариев по наставничеству,
     * которые привязаны к странице с идетификатором $id
     * @param int $id
     * @return string
     * @throws CException
     * @throws CHttpException
     */
	public function actionIndexMentor($id)
	{
	    return $this->renderPartial('comments',array(
	        'id'=>$id,
	        'model'=>$this->loadModelsMentor($id),
	        'urlUpdateStr' => 'comment/updateMentor',
	        'urlDeleteStr' => 'comment/deleteMentor',
	    ));
	}

    /**
     * Изменение комментария
     * Изменить комментарий может только администратор и автор
     * @param int $id идентификатор комментария
     * @return string
     * @throws CException
     * @throws CHttpException*@see UserInfo
     */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		
		if (Yii::app()->user->inRole('admin') || $model->username == UserInfo::inst()->userLogin)
		{
			if(isset($_POST['Comment']))
			{
				$model->attributes=$_POST['Comment'];
				if ($model->save())
				{
					echo 'OK';
					Yii::app()->end();
				}
				elseif (Yii::app()->request->isAjaxRequest)
				{				    
				    $errors = '';
				    foreach ($model->getErrors() as $err)
				    {				     
				        $errors .= implode('<br />', $err);
				    }				    
				    echo $errors;
				    Yii::app()->end();
				}
			}
			
			return  $this->renderPartial('_form',array(
				'id'=>$id,
				'model'=>$model,
				'modelAll'=>$this->loadModels($id),
			));
		}
		else
		{
			throw new CHttpException(403,'Доступ ограничен!');
		}
	}


    /**
     * Update comment mentor
     * @param int $id
     * @return string
     * @throws CException
     * @throws CHttpException
     */
	public function actionUpdateMentor($id)
	{
	    $model=$this->loadModelMentor($id);
	    
	    if (Yii::app()->user->inRole('admin') || $model->username == UserInfo::inst()->userLogin)
	    {
	        if(isset($_POST['Comment']))
	        {
	            $model->attributes=$_POST['Comment'];
	            if ($model->save())
	            {
	                echo 'OK';
	                Yii::app()->end();
	            }
	            elseif (Yii::app()->request->isAjaxRequest)
	            {
	                $errors = '';
	                foreach ($model->getErrors() as $err)
	                {
	                    $errors .= implode('<br />', $err);
	                }
	                echo $errors;
	                Yii::app()->end();
	            }
	        }
	        
	        return  $this->renderPartial('_form',array(
	            'id'=>$id,
	            'model'=>$model,
	            'modelAll'=>$this->loadModelsMentor($id),
	            'urlForm' => Yii::app()->controller->createUrl('comment/formMentor',array('id'=>$id)),
	            'urlUpdate' => Yii::app()->controller->createUrl('comment/updateMentor',array('id'=>$model->id)),
	        ));
	    }
	    else
	    {
	        throw new CHttpException(403,'Доступ ограничен!');
	    }
	}
	
	/**
	 * Поиск комментария по идентификатору
	 * @param int $id идентификатор комментария
	 * @throws CHttpException
	 * @see Comment
	 * @return Comment
	 * @uses actionDelete()
	 * @uses actionUpdate()
	 */
	private function loadModel($id)
	{
	    $model = Comment::model()->findByPk($id);	    
		if ($model===null)
			throw new CHttpException(404);
		return $model;
	}
		
	/**
	 * Find mentor comment
	 * @param int $id
	 * @throws CHttpException
	 * @return MentorComment
	 */
	private function loadModelMentor($id)
	{	    
	    $model = MentorComment::model()->findByPk($id);
	    if ($model===null)
	        throw new CHttpException(404);
        return $model;
	}

    /**
     * Поиск комментариев по родительскому идентификатору
     * @param int $id идентификатор комментария
     * @return array
     * @throws CException
     * @throws CHttpException*@uses actionForm()
     * @uses actionIndex()
     * @uses actionUpdate()
     */
	private function loadModels($id)
	{		
//		$model = Yii::app()->db->createCommand()
//		  ->from('{{comment}}')
//		  ->where('id_parent=:id_parent and date_delete is null', [':id_parent'=>$id])
//		  ->queryAll();
        $model = Comment::model()->findAll('id_parent=:id_parent ', [':id_parent'=>$id]);
		
        if ($model===null)
            throw new CHttpException(404,'Страница не найдена!');
        return $model;
	}


    /**
     * @param int $id
     * @return array|mixed
     * @throws CException
     * @throws CHttpException
     */
	private function loadModelsMentor($id)
	{
	    $model = Yii::app()->db->createCommand()
    	    ->from('{{mentor_comment}}')
    	    ->where('id_mentor_post=:id_post and date_delete is null', [':id_post'=>$id])
    	    ->queryAll();
	    
	    if ($model===null)
	        throw new CHttpException(404,'Страница не найдена!');
        return $model;
	}



}