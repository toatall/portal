<?php

class ConfigController extends AdminController
{
    public function actionIndex()
    {
        $model = new ConfigForm;        
        $config = new EConfig;
        
        foreach ($model->attributes as $attr => $val) {
            $model->$attr = $config->get($attr);
        }
        
        
        if (isset($_POST['222']))
        {
            $model->attributes = $_POST['222'];
            foreach ($model->attributes as $attr => $val) {                
                $config->set($attr, $val);
            }
            Yii::app()->user->setFlash('successesSaveConfig', 'Все ОК!!!');
        }
        
        //$form = new CForm('admin.views.config.configForm', $model);
        //if ($form->submitted('configPage') && $form->validate()) {

            
            
            
            
            $this->render('configForm', array('model' => $model));            
            
        //} else {
            //$this->render('config', array('form' => $form));
        //}
    }
}