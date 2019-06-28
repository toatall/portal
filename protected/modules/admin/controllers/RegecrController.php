<?php

/**
 * Manage Reg ECR
 * @author toatall
 * @see RegEcr
 *
 */
class RegecrController extends AdminController
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
                'expression'=>function() { return Yii::app()->user->inRole(['admin']); },
            ),
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
        $model = $this->loadModel($id);
        $this->checkAccess($model);
        $this->render('view',array(
            'model'=>$model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new RegEcr();
        $this->checkAccess($model);
        if (isset($_POST['RegEcr']))
        {
            $model->attributes=$_POST['RegEcr'];
            if($model->save())
            {
                $this->redirect(array('view','id'=>$model->id));
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
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['VoteMain']))
        {
            $model->attributes=$_POST['VoteMain'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);
            $model->date_delete = new CDbExpression('getdate()');
            $model->save(false,['date_delete']);

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }
	
    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new RegEcr('search');
        $this->checkAccess($model);	
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['RegEcr']))
            $model->attributes=$_GET['RegEcr'];

        $this->render('admin',array(
            'model'=>$model,          
        ));
    }	
		
    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer the ID of the model to be loaded
    */
    public function loadModel($id)
    {
        $model= RegEcr::model()->findByPk($id);
        $this->checkAccess($model);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
    
    /**
    * Проверка прав пользователя
    * @throws CHttpException
    * @uses self::actionCreate()
    * @uses self::actionAdmin()
    */
   private function checkAccess($model)
   {
        $idTree = $model->treeId;
        if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree)))
            throw new CHttpException(403,'Доступ запрещен.');
   }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
            if(isset($_POST['ajax']) && $_POST['ajax']==='vote-main-form')
            {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
            }
    }
}
