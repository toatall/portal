<?php

/**
 * Manage telephone glossary
 * @author alexeevich
 * @see AdminController
 */
class TelephoneController extends AdminController
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
			'postOnly + delete', // we only allow deletion via POST request
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
	 * @param integer $idTree идентификатор структуры
	 */
	public function actionView($id, $idTree)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id, $idTree),
            'idTree'=>$idTree,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @param integer $idTree идентификатор структуры
	 * @see Tree
	 * @see Access
	 * @see Telephone
	 * @throws CHttpException
	 */
	public function actionCreate($idTree)
	{   
        if (!Tree::model()->exists('id=:id AND module=:module', array(':id'=>$idTree,'module'=>'telephone')))
            throw new CHttpException(404,'Страница не найдена.');
		
        if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
            
    	$model=new Telephone;
        $model->id_tree = $idTree;
        $model->author = Yii::app()->user->name;

		if(isset($_POST['Telephone'])) {
			$model->attributes=$_POST['Telephone'];
			$model->log_change = Log::setLog($model->log_change, 'создание');
            
            $tempFile = CUploadedFile::getInstance($model, 'tel_file');
            if ($tempFile!=null) {
                $file_name = $model->id_organization.'_'.date('Ymd_His').'.'.pathinfo($tempFile->getName(), PATHINFO_EXTENSION);
                $model->telephone_file = $file_name;
                $tempFile->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::app()->params['pathTelephones'].'/'.$file_name);
            }            
            
            if($model->save()) {
				$this->redirect(array('view','id'=>$model->id, 'idTree'=>$idTree));
            }
		}

		$this->render('create',array(
			'model'=>$model,
            'idTree'=>$idTree,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 * @param integer $idTree идентификатор структуры
	 */
	public function actionUpdate($id, $idTree)
	{
		$model=$this->loadModel($id, $idTree);
        $model->id_tree = $idTree;
        $model->author = Yii::app()->user->name;                

		if(isset($_POST['Telephone']))
		{
            $model->attributes=$_POST['Telephone'];
            $model->log_change = Log::setLog($model->log_change, 'изменение');
            
            $tempFile = CUploadedFile::getInstance($model, 'tel_file');
            if ($tempFile!=null)
            {                
                try {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['pathTelephones'] . '/' . $model->telephone_file))
						@unlink($_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['pathTelephones'] . '/' . $model->telephone_file);
                } catch (exception $e) {}
                
                $file_name = $model->id_organization . '_' . date('Ymd_His') . '.' . pathinfo($tempFile->getName(), PATHINFO_EXTENSION);
                $model->telephone_file = $file_name;
                $tempFile->saveAs($_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['pathTelephones'] . '/' . $file_name);                
            }
			
			if($model->save())
				$this->redirect(array('view', 'id'=>$model->id, 'idTree'=>$idTree));
		}

		$this->render('update', array(
			'model'=>$model,
            'idTree'=>$idTree,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 * @param integer $idTree идентификатор структуры
	 */
	public function actionDelete($id, $idTree)
	{
        $model=$this->loadModel($id, $idTree);
        try {
            if ($model->telephone_file!='' && file_exists(Yii::app()->params['pathTelephones'] . '/' . $model->telephone_file))
                @unlink(Yii::app()->params['pathTelephones'] . '/' . $model->telephone_file);
        } catch (exception $e) {}		
        $model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'idTree'=>$idTree));
	}

	/**
	 * Manages all models.
	 * @param integer $idTree идентификатор структуры
	 * @see Tree
	 * @see Access
	 * @throws CHttpException
	 */
	public function actionAdmin($idTree)
	{	     
        if (!Tree::model()->exists('id=:id AND module=:module', array(':id'=>$idTree,'module'=>'telephone')))
            throw new CHttpException(404,'Страница не найдена.');
        
        if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
            
		$model=new Telephone('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Telephone']))
			$model->attributes=$_GET['Telephone'];
		
		$model->id_tree = $idTree;

		$this->render('admin',array(
			'model'=>$model,
            'idTree'=>$idTree,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @see Telephone
	 * @return Telephone the loaded model
	 * @throws CHttpException
	 * @uses self::actionView()
	 * @uses self::actionUpdate()
	 * @uses self::actionDelete()
	 */
	public function loadModel($id, $idTree)
	{
	    if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree))
            || !Tree::model()->checkParentRight($idTree))
            throw new CHttpException(403,'Доступ запрещен.');
        
        $criteria=new CDbCriteria;
        $criteria->compare('id',$id);
        $criteria->addInCondition('id_organization',
            CHtml::listData(Telephone::model()->listOrganizations($idTree),'code','code'));		
        $model=Telephone::model()->find($criteria);
        
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		
        if(!$model->checkAccessOrganization())
            throw new CHttpException(403,'Доступ запрещен.');
        
		return $model;
	}
    
    /**
     * Получение checkBoxList со списком групп или пользователей 
     * (которые подключены к текущей ветке Tree)
     * Используется для настройки дополнительных прав
     * @param int $id идентификатор структуры
     * @param int $identity идентификатор группы или пользователя
     * @param bool $is_group флаг показывающий, что в качестве $identity передается идентификатор группы
     * @see Group
     * @see User
     * @return string
     */
    public function actionAjaxTreeAccess($id, $identity, $is_group)
    {
        if (/*!Yii::app()->request->isAjaxRequest || */
            !isset($id) || !isset($identity) || !isset($is_group) || !Yii::app()->user->admin) return;            
        
        $record = ($is_group) ? Group::model()->findByPk($identity)
            : User::model()->findByPk($identity);
        if ($record===null) return;
        
        $postfix = ($is_group) ? 'Group' : 'User';
        
        if ($is_group)
        {
            echo CHtml::checkBoxList('Tree[OptionalAccess]['.$postfix.'][]', 
                CHtml::listData(Yii::app()->db->createCommand()
                    ->select('t.*')
                    ->from('{{access_organization_group}} t')
                    ->join('{{access_group}} a', 'a.id = t.id_access_group')
                    ->where('a.id_group=:id_group AND a.id_tree=:id_tree',
                        array(':id_group'=>$identity, ':id_tree'=>$id))
                    ->queryAll()
                ,'id','id_organization'),
                CHtml::listData(Organization::model()->findAll(),'code','name'),
            array(
                'separator'=>'',
                'template'=>'<label class="checkbox">{input} {label}</label>',            
            ));
        }
        else
        {
            echo CHtml::checkBoxList('Tree[OptionalAccess]['.$postfix.'][]', 
                CHtml::listData(Yii::app()->db->createCommand()
                    ->select('t.*')
                    ->from('{{access_organization_user}} t')
                    ->join('{{access_user}} a', 'a.id = t.id_access_user')
                    ->where('a.id_user=:id_identity AND a.id_tree=:id_tree',
                        array(':id_identity'=>$identity, ':id_tree'=>$id))
                    ->queryAll()
                ,'id','id_organization'),
                CHtml::listData(Organization::model()->findAll(),'code','name'),
            array(
                'separator'=>'',
                'template'=>'<label class="checkbox">{input} {label}</label>',            
            ));
        }
        
        echo '<script type="text/javascript">
                $(function() {
                    $("#'.CHtml::getIdByName('Tree[OptionalAccess]['.$postfix.'][]').'").find("[type=checkbox]").click(function() {
                        var checkStatus = $(this).is(":checked") ? 1 : 0;
                        jQuery.ajax({
                            "type":"POST",
                            "url":"'.$this->createUrl('/admin/telephone/ajaxUpdateTreeAccess').'",
                            "data":{"id":'.$id.',"org":$(this).val(),"check":checkStatus,"identity":'
                                .$identity.',"is_group":'.$is_group.'},
                        });                                           
                    });                                      
                });                
            </script>';        
    }
    
    // разделить на 2 части приватные и отсюда управлять
    /**
     * Обновление прав для телефонного справочника
     * @see Group
     * @see User
     * @see Organization
     * @see AccessGroup
     * @see AccessUser
     * @return string
     */
    public function actionAjaxUpdateTreeAccess()
    {
        if (!Yii::app()->request->isAjaxRequest 
            || !isset($_POST['id']) || !isset($_POST['org']) || !isset($_POST['check'])
            || !isset($_POST['is_group']) || !isset($_POST['identity']) 
            || !is_numeric($_POST['id']) || !is_numeric($_POST['org']) 
            || !is_numeric($_POST['check']) || !is_numeric($_POST['is_group'])
            || !is_numeric($_POST['identity']) || !Yii::app()->user->admin) return 'not isset';
        
        $identity = $_POST['is_group'] ? Group::model()->findByPk($_POST['identity'])
            : User::model()->findByPk($_POST['identity']);
        
        if (Organization::model()->exists('code=:code', array(':code'=>$_POST['org'])) && 
            Tree::model()->exists('id=:id', array(':id'=>$_POST['id'])) && $identity!==null) 
        {
            if ($_POST['is_group'])
            {
                $modelAccess = AccessGroup::model()->find('id_tree=:id_tree and id_group=:id_group and id_organization=:id_organization', array(
                    ':id_tree'=>$_POST['id'],
                    ':id_group'=>$_POST['identity'],
                    ':id_organization'=>Yii::app()->session['organization'],
                ));
            }
            else 
            {
                $modelAccess = AccessUser::model()->find('id_tree=:id_tree and id_user=:id_user and id_organization=:id_organization', array(
                    ':id_tree'=>$_POST['id'],
                    ':id_user'=>$_POST['identity'],
                    ':id_organization'=>Yii::app()->session['organization'],
                ));
            }
            
            if ($modelAccess===null)
                echo 'modelAccess is null';
            
            // если установили галочку...
            if ($_POST['check'])
            {
                if ($_POST['is_group'])
                {
                    Yii::app()->db->createCommand()->insert('{{access_organization_group}}', array(                    
                        'id_access_group'=>$modelAccess->id,                        
                        'id_organization'=>$_POST['org'],
                        'author' => Yii::app()->user->name,
                        'date_create' => new CDbExpression('getdate()'),
                    ));
                }
                else
                {
                    Yii::app()->db->createCommand()->insert('{{access_organization_user}}', array(                    
                        'id_access_user'=>$modelAccess->id,                        
                        'id_organization'=>$_POST['org'],                        
                        'author' => Yii::app()->user->name,
                        'date_create' => new CDbExpression('getdate()'),
                    ));
                }
            }
            // ...или убрали галочку
            else
            {
                if ($_POST['is_group'])
                {
                    Yii::app()->db->createCommand()->delete('{{access_organization_group}}', 
                        'id_access_group=:id_access_group AND id_organization=:id_organization',
                        array(
                            'id_access_group'=>$modelAccess->id,                            
                            'id_organization'=>$_POST['org'],                            
                        )
                    );
                }
                else
                {
                    Yii::app()->db->createCommand()->delete('{{access_organization_user}}',
                        'id_access_user=:id_access_user AND id_organization=:id_organization',
                        [
                            'id_access_user'=>$modelAccess->id,                            
                            'id_organization'=>$_POST['org'],
                        ]
                    );
                }
            }
        }
        else
        {
            echo 'Not found organization or id tree';
        }
    }
    
	/**
	 * Performs the AJAX validation.
	 * @param Telephone $model the model to be validated
	 * @deprecated
	 */
	protected function performAjaxValidation($model)
	{
	    throw new CHttpException(410);
		if(isset($_POST['ajax']) && $_POST['ajax']==='telephone-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
