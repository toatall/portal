<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'menu-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
    
    <?php $array_parent = Yii::app()->user->admin ? array(0=>'Родитель') : array(); ?>     
    <?php if ($model->isNewRecord): ?>    
    <?php echo $form->dropDownListRow($model,'id_parent',$array_parent
        + Menu::model()->getMenuDropDownList($model->type_menu)); ?>
    <?php else: ?>    
    <?php echo $form->dropDownListRow($model,'id_parent',$array_parent
        + Menu::model()->getMenuDropDownList($model->type_menu, $model->id)); ?>        
    <?php endif; ?>
    
    <p><?php echo $model->getAttributeLabel('type_menu'); ?>: 
    <?php
        switch ($model->type_menu)
        {
            case 1: echo 'Верхнее меню'; break;
            case 2: echo 'Левое меню'; break;
        }
    ?></p>
    
	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'link',array('class'=>'span5','maxlength'=>500)); ?>
    
    <?php echo $form->dropDownListRow($model,'target',
        array(''=>'', '_self'=>'_self', '_blank'=>'_blank', '_parent'=>'_parent', '_top'=>'_top')); ?>
    
	<?php echo $form->textAreaRow($model,'submenu_code',array('class'=>'span5','maxlength'=>1000,
        'style'=>'color:#038C01;')); ?>
	
	<?php echo $form->checkBoxRow($model,'blocked',array()); ?>
    
    <?php echo $form->textFieldRow($model,'sort_index',array('class'=>'span3')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
