<?php

class BreadcrumbsHelper extends CComponent
{
    
    const SEPARATOR = ' / ';
    
    /**
     * Экземпляр объекта
     * @var self
     */
    private static $instance = null;
    
    /**
     * Возвращает экземпляр текущего объекта
     * @return self
     */
    public static function app()
    {
        if (self::$instance === null)
            self::$instance = new self();
        
        return self::$instance;
    }
    
    /**
     * Основной путь
     */
    public function brGeneral($idTree)
    {
        $model = Yii::app()->db->createCommand()
            ->from('{{tree}}')
            ->where('id=:id', [':id'=>$idTree])
            ->queryRow();
        
        $link = null;
        
        if ($model['module'] != null)
        {
            $link =  array('/' . $model['module'], 'idTree' => $idTree);
        }
            
        if ($model['id_parent'] == 0)
            return array($model['name'] => $link);
        
        return array_merge($this->brGeneral($model['id_parent']), array($model['name'])); 
    }
    
}