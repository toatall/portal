<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'sez-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->dropDownListRow($model,'code_no',
			CHtml::listData(User::userOrganizations(),'code','fullName'),
        	array('class'=>'span8','maxlength'=>50)); ?>

	<?php echo $form->dropDownListRow($model,'type_sez', $model->listTypeSez, array('class'=>'span8')); ?>
	
	<?php echo $form->textAreaRow($model,'message',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Далее',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
