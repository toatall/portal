<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'profile-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
	),
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php if (isset($ldapError) && $ldapError!==null): ?>
	<div class="alert alert-danger">
		Не удалось получить информацию о пользователе из ActiveDirectory.<br />
		<?= $ldapError ?>
	</div>
	<?php endif; ?>
	
	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'telephone',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'telephone_ip',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'post',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'rank',array('class'=>'span5','maxlength'=>250)); ?>
	
	<?php if (!$model->isNewRecord && !empty($model->photo_file)): ?>		
		<?= CHtml::image(Yii::app()->params['urlProfiles'] 
			. $model->photo_file, $model->name, array('class'=>'thumbnail')) ?>
		<?= $form->checkBoxRow($model, 'delete_image') ?>				
	<?php endif; ?>
	
	<?php echo $form->fileFieldRow($model,'photo_file'); ?>

	<?php echo $form->textAreaRow($model,'about',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php //echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>500)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
