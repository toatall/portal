<?php

/**
 * 
 * @author 8600-90331
 * @deprecated
 */
class ProfkomController extends Structure
{
    
    protected $alias = 'profkom';
    
    /**
     * Информация по ФКУ
     * @desc поиск в Tree строки с алиасом fku и передача его id функции indexByIdTree
     * @author oleg
     * @version 18.10.2017
     */
    public function actionIndex($id=null)
    {
        $modelProfkom = Yii::app()->db->createCommand()
                ->from('{{tree}}')
                ->where('alias=:alias', [':alias'=>$this->alias])
                ->queryRow();
        
        if ($id!=null)       
        {
            // поиск раздела с алислом $this->alias
            $modelTree = Yii::app()->db->createCommand()
                ->from('{{tree}}')
                ->where('id=:id', [':id'=>$id])
                ->queryRow();
        }
        else
        {
            $modelTree = $modelProfkom;
        }
        
        if ($modelTree==null)
            throw new CHttpException(404,'The requested page does not exist.');
        
           
        Menu::$leftMenuAdd = array_merge(Menu::$leftMenuAdd, $this->getMenu($modelProfkom['id']));
            
        $this->indexByIdTree($modelTree['id']);
    }
    
}