<?php

class TelephoneController extends AdminController
{
	
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
	 */
	public function actionCreate($idTree)
	{
	           
        if (!Tree::model()->exists('id=:id AND module=:module', array(':id'=>$idTree,'module'=>'telephone')))
            throw new CHttpException(404,'Страница не найдена.');
		
        if (!(Yii::app()->user->admin || Access::model()->checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
            
    	$model=new Telephone;
        $model->id_tree = $idTree;
        $model->author = Yii::app()->user->name;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Telephone']))
		{
			$model->attributes=$_POST['Telephone'];
			//$model->log_change = LogChange::setLog($model->log_change, 'создание');
            
            $tempFile=CUploadedFile::getInstance($model, 'tel_file');
            if ($tempFile!=null)
            {
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
	 */
	public function actionUpdate($id, $idTree)
	{
	           
		$model=$this->loadModel($id, $idTree);
        $model->id_tree = $idTree;
        $model->author = Yii::app()->user->name;                

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Telephone']))
		{
            $model->attributes=$_POST['Telephone'];
            //$model->log_change = LogChange::setLog($model->log_change, 'изменение');
            
            $tempFile=CUploadedFile::getInstance($model, 'tel_file');
            if ($tempFile!=null)
            {                
                try {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->params['pathTelephones'].'/'.$model->telephone_file))
						unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->params['pathTelephones'].'/'.$model->telephone_file);
                } catch (exception $e) {}
                
                $file_name = $model->id_organization.'_'.date('Ymd_His').'.'.pathinfo($tempFile->getName(), PATHINFO_EXTENSION);
                $model->telephone_file = $file_name;
                $tempFile->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::app()->params['pathTelephones'].'/'.$file_name);                
            }
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id,'idTree'=>$idTree));
		}

		$this->render('update',array(
			'model'=>$model,
            'idTree'=>$idTree,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id, $idTree)
	{
        $model=$this->loadModel($id, $idTree);
        try {
            if ($model->telephone_file!='' && file_exists(Yii::app()->params['pathTelephones'].'/'.$model->telephone_file))
                unlink(Yii::app()->params['pathTelephones'].'/'.$model->telephone_file);
        } catch (exception $e) {}
		//$this->loadModel($id, $idTree)->delete();
        $model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}


	/**
	 * Manages all models.
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

		$this->render('admin',array(
			'model'=>$model,
            'idTree'=>$idTree,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Telephone the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id, $idTree)
	{
	    if (!(Yii::app()->user->admin || Access::model()->checkAccessUserForTree($idTree))
            || !Tree::model()->checkParentRight($idTree))
            throw new CHttpException(403,'Доступ запрещен.');
        
        $criteria=new CDbCriteria;
        $criteria->compare('id',$id);
        $criteria->addInCondition('id_organization',
            CHtml::listData(Telephone::model()->listOrganizations($idTree),'code','code'));		
        $model=Telephone::model()->find($criteria);
        
		if($model===null)
			throw new CHttpException(404,'Страница не найдена.');
		return $model;
	}
    
    
    public function actionAjaxTreeAccess($id, $identity, $is_group)
    {
        if (!Yii::app()->request->isAjaxRequest
            || !isset($id) || !isset($identity) || !isset($is_group) || !Yii::app()->user->admin) return;            
                
        
        $record = ($is_group) ? Group::model()->findByPk($identity)
            : User::model()->findByPk($identity);
        if ($record===null) return;
        
        $postfix = ($is_group) ? 'Group' : 'User';
        
        echo CHtml::checkBoxList('Tree[OptionalAccess]['.$postfix.'][]', 
            CHtml::listData(Yii::app()->db->createCommand()
                ->select('*')
                ->from('{{access_telephone}}')
                ->where('id_identity=:id_identity AND id_tree=:id_tree AND is_group=:is_group',
                    array(':id_identity'=>$identity, ':is_group'=>$is_group, ':id_tree'=>$id))
                ->queryAll()
            ,'id','id_organization'),
            CHtml::listData(Organization::model()->findAll(),'code','name'),
        array(
            'separator'=>'',
            'template'=>'<label class="checkbox">{input} {label}</label>',            
        ));
        
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
    
    public function actionAjaxUpdateTreeAccess()
    {
        
        if (!Yii::app()->request->isAjaxRequest 
            || !isset($_POST['id']) || !isset($_POST['org']) || !isset($_POST['check'])
            || !isset($_POST['is_group']) || !isset($_POST['identity']) 
            || !is_numeric($_POST['id']) || !is_numeric($_POST['org']) 
            || !is_numeric($_POST['check']) || !is_numeric($_POST['is_group'])
            || !is_numeric($_POST['identity']) || !Yii::app()->user->admin) return;
        
        $identity = $_POST['is_group'] ? Group::model()->findByPk($_POST['identity'])
            : User::model()->findByPk($_POST['identity']);
        
        if (Organization::model()->exists('code=:code', array(':code'=>$_POST['org'])) && 
            Tree::model()->exists('id=:id', array(':id'=>$_POST['id'])) && $identity!==null) 
        {
            if ($_POST['check'])
            {
                Yii::app()->db->createCommand()->insert('{{access_telephone}}', array(
                    'id_tree'=>$_POST['id'],
                    'id_identity'=>$_POST['identity'],
                    'id_organization'=>$_POST['org'],
                    'is_group'=>$_POST['is_group'],  
                	'date_create' => new CDbExpression('getdate()'),
                ));
            }
            else
            {
                Yii::app()->db->createCommand()->delete('{{access_telephone}}', 
                    'id_tree=:id_tree AND id_identity=:id_identity 
                        AND id_organization=:id_organization AND is_group=:is_group',
                    array(
                        'id_tree'=>$_POST['id'],
                        'id_identity'=>$_POST['identity'],
                        'id_organization'=>$_POST['org'],
                        'is_group'=>$_POST['is_group'],
                    )
                );
            }
        }       
    }
    
    
	/**
	 * Performs the AJAX validation.
	 * @param Telephone $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='telephone-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
