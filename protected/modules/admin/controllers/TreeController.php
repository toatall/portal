<?php

/**
 * Manage tree
 * @author alexeevich
 * @see AdminController
 */
class TreeController extends AdminController
{
    /**
     * Default action
     * @var string
     */
    public $defaultAction = 'admin';
    
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
            array('allow',                
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Создание нового узла
	 * @desc
	 * Параметры доступные только для пользователя с ролью администратор
	 * $allOrganization - для свех налоговых органов (для остальных пользователей = 0)
	 * $module - модуль (для остальных пользователей news)
	 * @see Tree
	 * @see Access
	 */
	public function actionCreate()
	{
		$model=new Tree;
		$model->module = Tree::defaultModule;

		// save model
		if(isset($_POST['Tree']))
		{            
			$model->attributes=$_POST['Tree'];
			
			if (!Yii::app()->user->admin)
			{
				$model->useParentRight=true;
			}
			
            if (!$model->use_material)
            {
                $model->use_organization = false;
                $model->use_tape = false;
                $model->module = null;
            }
            
			if($model->save())
            {                      
                $permissionGroups = (isset($_POST['Tree']['permissionGroup'])) 
                    ? $_POST['Tree']['permissionGroup'] : array();
                $permissionUsers = (isset($_POST['Tree']['permissionUser'])) 
                    ? $_POST['Tree']['permissionUser'] : array();
                
                Access::saveRelationsPermissions($model->id, $model, $permissionGroups, $permissionUsers);
                
                /* дополнительные настройки прав */
                $flagDopAccessModel=false;
                if (($model->module != null) && (class_exists($model->module, false)) && (Yii::app()->user->admin))
                {
                    $dopAccessModel = new $model->module;
                    if ($dopAccessModel) $flagDopAccessModel=true;
                }
                
                if ($flagDopAccessModel)
                {
                    $this->redirect(array('access','id'=>$model->id));
                }
                else 
                {
                    $this->redirect(array('view','id'=>$model->id));
                }
            }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 * @see Access
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		
		if(isset($_POST['Tree']))
		{	
			$model->attributes=$_POST['Tree'];
            if (!$model->use_material)
            {
                $model->use_organization = false;
                $model->use_tape = false;
                $model->module = null;
            }            
            
			if($model->save())
            {      
                if (Yii::app()->user->admin)
                {
                    $permissionGroups = (isset($_POST['Tree']['permissionGroup'])) 
                        ? $_POST['Tree']['permissionGroup'] : array();
                    $permissionUsers = (isset($_POST['Tree']['permissionUser'])) 
                        ? $_POST['Tree']['permissionUser'] : array();
                    
                    Access::saveRelationsPermissions($model->id, $model, $permissionGroups, $permissionUsers);
                    
                    /* дополнительные настройки прав */
                    $flagDopAccessModel=false;
                    
                    if (($model->module != null) && (Yii::app()->user->admin))
                    {                    
                        if (@class_exists($model->module))
                        {
                            $dopAccessModel = new $model->module;
                            if ($dopAccessModel->hasProperty('useOptionalAccess') && $dopAccessModel->useOptionalAccess) 
                                $flagDopAccessModel = true;
                            if (property_exists($model->module, 'useOptionalAccess'))
                                $flagDopAccessModel = true;                            
                        }                    
                    }
                    
                    if ($flagDopAccessModel)
                    {
                        return $this->redirect(array('access','id'=>$model->id));
                    }                    
                }                
                return $this->redirect(array('view','id'=>$model->id));                                 
            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
        
	/**
	 * Дополнительные настройки доступа (например, настройка доступа к организациям)
	 * @param int $id идентификатор узла структуры
	 * @throws CHttpException	
	 */
    public function actionAccess($id)
    {
        if (!Yii::app()->user->admin)
            throw new CHttpException(403,'У вас недостаточно прав для выполнения указанного действия.');
        
        $model=$this->loadModel($id);
        
        $flagAccess = false;
       	if (class_exists($model->module))
       	{
        	$moduleClass = new $model->module;
        	
        	if (($moduleClass->hasProperty('useOptionalAccess') || property_exists($moduleClass, 'useOptionalAccess'))
        			&& $moduleClass->model()->useOptionalAccess)
        	{
        		$flagAccess = true;
        	}
        
            if ($flagAccess)
            {
                return $this->render('../' . $model->module . '/access', array(
            		'model'=>$model,
                    'module'=>$moduleClass,
                ));
            }
       	}        
       	return $this->redirect(array('view','id'=>$id));                
    }
    
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 * @throws CHttpException
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
            if (!Yii::app()->user->admin)
            {
                $model = $this->loadModel($id);
                $model->date_delete = new CDbExpression('getdate()');
                $model->save();                
            }
            else
            {
                // we only allow deletion via POST request
                $this->loadModel($id)->delete();
            }
			

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * Manages all models.
	 * @see Tree
	 * @throws CHttpException
	 */
	public function actionAdmin()
	{
		$model=new Tree('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tree']))
			$model->attributes=$_GET['Tree'];
		
		$tree = Tree::model()->getTree();
		
		if ($tree==null && !Yii::app()->user->admin)
			throw new CHttpException(403,'У вас недостаточно прав для выполнения указанного действия.');
			
		$this->render('admin',array(
			'model'=>$model,
			'tree' => $tree,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 * @see Access
	 * @see Tree
	 * @uses self::actionView()
	 * @uses self::actionUpdate()
	 * @uses self::actionAccess()
	 * @uses self::actionDelete()
	 */
	public function loadModel($id)
	{ 		
        $model=Tree::model()->find(array(
        	'condition'=>'id=:id'
                // дополнительное условие для проверки прав пользователя
            	. (!Yii::app()->user->admin && !Access::checkAccessUserForTree($id) ? ' and 1<>1' : ''),
            'params'=>array(
            	':id'=>$id, 
            )
        ));        
		if ($model===null || (!Tree::model()->checkParentRight($model->id_parent)))
			throw new CHttpException(404,'Запрашиваемая страница не существует.');        
        
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 * @deprecated
	 */
	protected function performAjaxValidation($model)
	{
	    throw new CHttpException(410);
		if(isset($_POST['ajax']) && $_POST['ajax']==='Tree-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    /** Информация по пользователям для предоставления доступа
     * @desc Если пользователь не имеет прав администратора, то
     * отображаются только пользователи с кодом НО,
     * который установлен у текущего пользователя (User.home_no)
     * @see User    
     **/	
    public function actionGetListUser()
    {
        if (!Yii::app()->request->isAjaxRequest) return;                 
        
        $model = new User('sarchForTree');        
        $model->unsetAttributes();  // clear any default values
        $model->default_organization = Yii::app()->session['organization'];
        
        if(isset($_GET['User']))
			$model->attributes=$_GET['User'];
        
		if (isset($_POST['users']))
		{
			$model->users = $_POST['users'];
		}
			
        $this->renderPartial('_ajaxListPermission', array(
            'model'=>$model,
            'is_group'=>false,
        ), false, true);                
    }
    
    
    /** Информация по группам для предоставления доступа
     * @desc Если текущий пользователь не имеет прав администратора, то 
     * отображаются только группы с кодом НО,
     * который установлен у текущего пользователя (User.home_no)
     * @see Group 
     **/
    public function actionGetListGroup()
    {            	
        if (!Yii::app()->request->isAjaxRequest) return;
        
        $model = new Group('sarchForTree');
        $model->unsetAttributes();  // clear any default values
        $model->id_organization = Yii::app()->session['organization'];        
        
        if(isset($_GET['Group']))
			$model->attributes=$_GET['Group'];        
			
		if (isset($_POST['groups']))
		{
			$model->groups = $_POST['groups'];
		}
        
        $this->renderPartial('_ajaxListPermission', array(
            'model'=>$model,
            'is_group'=>true,
        ), false, true);
    }
    
    /**
     * @deprecated
     * @param unknown $module
     */
    public function actionGetTreeRight($module)
    {
        throw new CHttpException(410);
        
        if (!Yii::app()->request->isAjaxRequest) return;
        
        $model = Module::model()->findByPk($module);       
        if ($model!==null && ($model->name!=''))
        {
           $this->renderPartial('../'.$model->name.'/_accessTree');
        }
        
        
    }
         
    
    
}
