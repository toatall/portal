<?php
/**
 * Компонент проверки прав пользователей
 * 
 * Дата создания: 07.08.2014
 * Дата изменения: 08.08.2014
 * 
 **/
 
class LogChange extends CComponent
{

    
    public static function getLog($record)
    {
        $explode_array = explode('$',$record);
        $array_str = array();
        foreach ($explode_array as $val)
        {
            if ($val != '')
            {                
                $array_str[] = str_replace('|', ' - ', $val);
            }
        }
        
        return Yii::app()->controller->renderPartial(
            'application.modules.admin.components.views.viewLogChange',
            array('array_str'=>array_reverse($array_str)),
            true
        );
    }
    
    
    
    /**
     * Функция возвращает запись для лога
     * **/
    public static function setLog($lastRecord, $operation)
    {
        return $lastRecord.'$'.date('d.m.Y H:i:s').'|'.$operation.'|'
            .(isset(Yii::app()->user->name) ? Yii::app()->user->name : 'guest');
    }
    
    
}