<?php

/**
 * @deprecated
 * @author 8600-90331
 *
 */
class ProfileController extends AdminController
{
	
	
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
				'expression'=>function() { return Yii::app()->user->inRole(['admin']); },
			),
			
            array('deny',  // deny all users
				'users'=>array('*'),
			),			            
		);
	}
	

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id)
	{
	    throw  new CHttpException(410);
		$model=new Profile();
		
		if (Profile::model()->findByPK($id))
			throw new CHttpException(500,'Профиль уже создан.');
		
	
		$modelUser = User::model()->findByPk($id);
		if($modelUser===null)
			throw new CHttpException(404,'The requested page does not exist.');
	
		$model->id = $modelUser->id;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        
		if(isset($_POST['Profile']))
		{
            $model->attributes=$_POST['Profile'];
			if($model->save())
            {                
            	$this->saveProfileImage($model);
				$this->redirect(array('user/view','id'=>$model->id));
            }
		}
		else
		{			
			// попытка получения информации из AD
			$ldapError = null;			
			if ($modelUser !== null)
			{			
				$ldapInfo = new LDAPInfo();
							
				if ($ldapInfo->getInfoAD($modelUser->username_windows))
				{										
					$model->name =  ($ldapInfo->cn!=null ? $ldapInfo->cn : $ldapInfo->displayname);
					$model->telephone_ip = $ldapInfo->telephonenumber;
					$model->post = $ldapInfo->title;					
				}	
				$ldapError = $ldapInfo->getError();			 				
			}
		}
		
		$this->render('create',array(
			'model'=>$model,
			'ldapError'=>$ldapError,
		));
	}
	
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
	    throw new CHttpException(410);
		$model=Profile::model()->findByPk($id);
		if ($model === null)
			$this->redirect(array('create', 'id'=>$id));
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Profile']))
		{
			$model->attributes=$_POST['Profile'];
			
			if($model->save())
            {                
            	$this->saveProfileImage($model, $model->delete_image);
                $this->redirect(array('user/view','id'=>$model->id));
            }				
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	
	/**
	 * Сохранение файла профиля в каталог Yii::app()->params['urlProfiles'] 
	 * 
	 * @param Profile $model - сохраняемая модель
	 * @param boolean $onlyDeleteImage - флаг для удаления фотографии 
	 * 		без последубющего сохранения
	 * 
	 */
	private function saveProfileImage($model, $onlyDeleteImage=false)
	{		
	    throw new CHttpException(410);
		$folder = $_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['urlProfiles'];
		
		if (!file_exists($folder))
			throw new CHttpException(500,"Каталог $folder не создан."); 				
		
		
		if ($onlyDeleteImage)
		{			
			if (file_exists($folder . $model->photo_file))				
				unlink($folder . $model->photo_file);	
			Yii::app()->db->createCommand()
				->update('{{profile}}', array(
						'photo_file' => new CDbExpression('null'),
				), 'id=:id', array(':id'=>$model->id));
			return;
		}
		
		$file = CUploadedFile::getInstance($model, 'photo_file');
		if (isset($file) && count($file)>0)
		{
			if (file_exists($folder . $model->photo_file))
				@unlink($folder . $model->photo_file);
			
			$imageHelper = new ImageHelper;
			
			if ($imageHelper->load($file->tempName))
			{
				if ($imageHelper->getHeight() > Yii::app()->params['heightImage'])
					$imageHelper->resizeToHeight(Yii::app()->params['heightImage']);
				
				$imageHelper->save($folder . $model->id . '.' . $file->getExtensionName());
				
				Yii::app()->db->createCommand()
					->update('{{profile}}', array(
						'photo_file' => $model->id . '.' . $file->getExtensionName(),
					), 'id=:id', array(':id'=>$model->id));
			}
		}		
	}
    	
    	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
	    throw new CHttpException(410);
		$model=Profile::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	


	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
    
    
    
    
    
}
