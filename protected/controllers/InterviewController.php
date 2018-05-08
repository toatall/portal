<?php

/**
 * Управление проведением голосования
 * @author alexeevich
 * @see Interview
 */
class InterviewController extends Controller
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
			 		'users'=>array('@'),
			 ),
		);
	}
	
	/**
	 * Информация о текущем голосовании и список вопросов для голосования
	 * @param int $id идентификатор голосования
	 * @throws CHttpException
	 * @see Interview	 
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
	    
	    // return view
	    $this->renderPartial('view', [
	        'modelInterview'=>$modelInterview,
	        'modelInterviewQuestion'=>$modelInterviewQuestion,
	        'countLikeUser'=>CHtml::listData($countLikeUser, 'id_interview_question', 'id_interview_question'),
	        'interviewExpiried'=>$modelInterview->isExpiried,
	    ]);
	}
	
	/**
	 * Кнопка для голосования
	 * В случае, если голосование окончено, либо пользователь уже голосовал,
	 * то вместо кнопки выводится соответствующая надпись
	 * @param int $id идентификатор вопроса
	 * @param int $idInterview идентификатор голосования
	 * @return string
	 * @todo перенсти часть с html в view
	 */
	public function actionLike($id, $idInterview)
	{	   	    
	    // голосование уже окончено
	    $modelInterview = Interview::model()->findByPk($idInterview);
	    if ($modelInterview===null)
	    {	        
	        echo '<span class="alert-error">Голосование с ИД ' . $idInterview . ' не найдено</span>';
	        Yii::app()->end();
	    }	    
	    if ($modelInterview->isExpiried) 
	    {	        
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
            echo '<span class="alert-error">Вы уже голосовали</span>';
	        Yii::app()->end();
        }
        
        // узнать количество голосов, если больше указанного в настройках, то ошибка
        if (count($interviewLike) >= $modelInterview->count_like)
        {            
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
	 * Голосование
	 * @param int $id идентификатор вопроса
	 * @deprecated
	 */
	public function actionAdd($id)
	{		
	    /*
		Yii::app()->db->createCommand('exec p_pr_like_news @username=:username, @ip_address=:ip_address, @id_parent=:id')
			->bindValue(':username', UserInfo::inst()->userLogin)
			->bindValue(':ip_address', UserInfo::inst()->clientIP)
			->bindValue(':id', $id)
			->execute();				
		echo $this->loadCountLike($id);
		*/
	    throw new CHttpException(410); // resource delete
	}
	
}