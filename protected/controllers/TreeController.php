<?php

class TreeController extends Controller
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
    
	
	
	
	public function actionView($id)
	{
	    // find id tree or http.error 404
	    $model = $this->loadModelTree($id);
	    $module = $model['module'];
	    
	    // run procedure to class or http.error 401
	    if (class_exists($module))
	    {	        
	        $modelModule = new $module;
	        if (method_exists($module, 'treeAction'))
	        {	            
	            return $modelModule->treeAction($model);
	        }
	    }
	        
	    throw new CHttpException(400,'Некорректный запрос.');

	}
	
	
	/**
	 * Find tree row
	 * @param int $id
	 * @throws CHttpException
	 * @return mixed
	 * @author oleg
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
