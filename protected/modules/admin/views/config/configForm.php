<?php
$this->breadcrumbs = array(
    'Настройки приложения',
);
?>


<?php
    
    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'config-form',
        'enableAjaxValidation'=>false,
    ));

    $this->widget('bootstrap.widgets.TbTabs', array(
        'id'=>'myTabs',       
        'type'=>'tabs',
        'encodeLabel'=>false,
        'tabs'=>array(
            array(               
                'label'=>'Основные', 
                'content'=>$this->renderPartial('_tab1', array('form'=>$form, 'model'=>$model), true, true), 
                'active'=>'true'),
            array(
                'label'=>'Системные', 
                'content'=>$this->renderPartial('_tab2', array('form'=>$form, 'model'=>$model), true, true),                        
            ),
        ),
    
    ));
    
    
?>
    
    <div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Сохранить',
		)); ?>
	</div>

<?php    
    
    $this->endWidget(); 
 
