<?php
/**
 * Приведение лога для чтения и записи в БД
 * @author alexeevich
 * @deprecated 
 **/
class LogChange 
{

    /**
     * Функция преобразует лог из БД к читаемому виду
     * @param string $record
     * @return string
     */
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
     * @param string $lastRecord текущий лог
     * @param string $operation выполняемая в данный момент операция
     * @return string
     * @uses
     * @see NewsController::actionCreate()
     */
    public static function setLog($lastRecord, $operation)
    {
        return $lastRecord.'$'.date('d.m.Y H:i:s').'|'.$operation.'|'
            .(isset(Yii::app()->user->name) ? Yii::app()->user->name : 'guest');
    }
    
    
}