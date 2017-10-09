<?php

class InterviewController extends Controller
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
	 * Информация о текущем голосовании и список вопросов для голосования
	 * @param int $id
	 * @author oleg
	 * @version 06.10.2017
	 */
	public function actionView($id)
	{
	    // 1 информация о указанном голосовании
	    $modelInterview = Interview::model()->findByPk($id);
	    if ($modelInterview === null)
	        throw new CHttpException(404,'Страница не найдена.');
	    
	    // 2 информация о вопросах
	    $modelInterviewQuestion = Yii::app()->db->createCommand()
	       ->from('{{interview_question}}')
	       ->where('id_interview=:id_interview', [':id_interview'=>$modelInterview->id])
	       ->order('count_like desc, date_create asc')
	       ->queryAll();
	    
	    // количество голосов у пользователя
	    $countLikeUser = Yii::app()->db->createCommand()	      
	       ->from('{{interview_like}} t')
	       ->join('{{interview_question}} q', 'q.id=t.id_interview_question')
	       ->where('t.username=:username and q.id_interview=:id_interview', [
	           ':username'=>Yii::app()->user->name,
	           ':id_interview'=>$id,
	       ])
	       ->queryAll();
	    
	       
	    // view
	    $this->renderPartial('view', [
	        'modelInterview'=>$modelInterview,
	        'modelInterviewQuestion'=>$modelInterviewQuestion,
	        'countLikeUser'=>CHtml::listData($countLikeUser, 'id_interview_question', 'id_interview_question'),
	        'interviewExpiried'=>$modelInterview->isExpiried,
	    ]);
	}
	
	
	/**
	 * Голосование
	 * @param int $id
	 * @param int $idInterview
	 */
	public function actionLike($id, $idInterview)
	{	   
	    
	    // голосование уже окончено
	    $modelInterview = Interview::model()->findByPk($idInterview);
	    if ($modelInterview===null)
	    {
	        /*echo CJSON::encode([
	            'status'=>'error',
	            'message'=>'Голосование с ИД ' . $idInterview . ' не найдено',
	        ]);*/
	        echo '<span class="alert-error">Голосование с ИД ' . $idInterview . ' не найдено</span>';
	        Yii::app()->end();
	    }	    
	    if ($modelInterview->isExpiried) 
	    {
	        /*echo CJSON::encode([
	            'status'=>'error',
	            'message'=>'Голосование уже окончено',
	        ]);*/
	        echo '<span class="alert-error">Голосование уже окончено</span>';
	        Yii::app()->end();
	    }
	    
	    // поиск InterviewQuestion за который голосуем
	    $model = Yii::app()->db->createCommand()
	       ->from('{{interview_question}}')
	       ->where('id=:id', [':id'=>$id])
	       ->query();
	    if ($model === null)
	    {
	        /*echo CJSON::encode([
	            'status'=>'error',
	            'message'=>'Не найдена запись с ИД ' . $id,
	        ]);*/
	        echo '<span class="alert-error">Не найдена запись с ИД ' . $id . '</span>';
	        Yii::app()->end();
	    }
	    
	    // проверить, что пользователь не голосовал
	    $interviewLike = CHtml::listData(Yii::app()->db->createCommand()
	        ->select('l.id_interview_question')
	        ->from('{{interview_like}} l')
	        ->leftJoin('{{interview_question}} q', 'l.id_interview_question=q.id')
	        ->leftJoin('{{interview}} t', 'q.id_interview=t.id')	        
            ->where('t.id=:idInterview and l.username=:username', [
                ':idInterview'=>$idInterview,
                ':username'=>Yii::app()->user->name,
            ])->queryAll(), 'id_interview_question', 'id_interview_question');
	    	   
        if (isset($interviewLike[$id]))
        {
            /*echo CJSON::encode([
	            'status'=>'error',
	            'message'=>'Вы уже голосовали',
	        ]);*/
            echo '<span class="alert-error">Вы уже голосовали</span>';
	        Yii::app()->end();
        }
        
        // узнать количество голосов, если больше указанного в настройках, то ошибка
        if (count($interviewLike) >= $modelInterview->count_like)
        {
            /*echo CJSON::encode([
	            'status'=>'error',
	            'message'=>'Количество голосов превышено!',
	        ]);*/
            echo '<span class="alert-error">Количество голосов превышено!</span>';
	        Yii::app()->end();
        }
        
	    // записать голос в таблице InterviewLike
        $inertLike = Yii::app()->db->createCommand('exec p_pr_like_interview @username=:username, @id_interview_question=:id')
            ->bindValue(':username', UserInfo::inst()->userLogin)
            ->bindValue(':id', $id)
            ->execute();		
        $modelCountLike = Yii::app()->db->createCommand()
            ->select('count(*)')
            ->from('{{interview_like}}')
            ->where('id_interview_question=:id', [':id'=>$id])
            ->queryScalar();
            
        echo '<span class="ic-like"></span> Голосовать <span class="badge" style="background: #3d6899;">' . $modelCountLike .'</span>';	    
	    Yii::app()->end();
	    
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
	/*
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

    */

}