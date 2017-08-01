<?php

class Statistic extends CComponent
{
	
	
	
	public static function StatisticOnlineAndDay()
	{
		$model = Yii::app()->db->createCommand('
			select t1.count_online, t2.count_day from			
				(select COUNT(distinct username) as count_online from {{log_authenticate}}
					where last_action >= DATEADD(minute,-5,getdate())) as t1
					,
				(select COUNT(distinct username) as count_day from {{log_authenticate}}
					where last_action >= CAST(convert(varchar,getdate(),104) as datetime)) as t2
			')->queryAll();		
		if ($model!==null && count($model)>0)
		{
			return '<span class="label label-success">Количество пользователей онлайн: ' . (isset($model[0]['count_online']) ? $model[0]['count_online'] : '')
				. '</span><br /><span class="label label-info">Количество пользователей за сегодня: ' . (isset($model[0]['count_day']) ? $model[0]['count_day'] : '') . '</span>';
		}
		return '';
	}
	
	
}