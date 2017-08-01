<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'setting-form-general',
	'enableAjaxValidation'=>false,
    
)); ?>
    
    <?php if(Yii::app()->user->hasFlash('success-general')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success-general'); ?>
    </div>
    <?php endif; ?>

    
    <?php
        $model = Setting::model()->findAll('t.group="general"');
        foreach ($model as $value):
        
            echo CHtml::label($value->description, $value->key);
            echo CHtml::textField($value->key, $value->value);            
        
        endforeach;
    ?>    	

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>