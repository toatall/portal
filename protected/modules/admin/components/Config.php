<?php

 
class Config extends CComponent
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
            .Yii::app()->user->name.' ('.Yii::app()->user->last_name
                .' '.Yii::app()->user->first_name.' '.Yii::app()->user->middle_name.')';
    }
    
    
}