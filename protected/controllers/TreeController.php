<?php

/**
 * Управление структурой
 * @author alexeevich
 * @see Tree
 */
class TreeController extends Controller
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
	 * Просмотр структуры
	 * @param int $id идентификатор структуры
	 * @throws CHttpException
	 */
	public function actionView($id)
	{	   
	    $model = $this->loadModelTree($id);
	    $module = $model['module'];
	    
        // run procedure to class or http.error 401
        if (@class_exists($module))
        {
            $modelModule = new $module;
            if (method_exists($module, 'treeAction'))
            {
                return $modelModule->treeAction($model);
            }
            else
            {
                echo 'Not found treeAction!'; return;
            }
        }	   
        
	    throw new CHttpException(400,'Некорректный запрос.');
	}
	
	/**
	 * Поиск записи по идентификатору
	 * @param int $id идентификатор структуры
	 * @throws CHttpException
	 * @return mixed
	 * @uses actionView()
	 */
	private function loadModelTree($id)
	{
	    $model = Yii::app()->db->createCommand()
	       ->from('{{tree}}')
	       ->where('id=:id', [':id'=>$id])
	       ->queryRow();
	    
	    if ($model==null)
	        throw new CHttpException(404,'Страница не найдена.');        
        return $model;
	}
	
}
