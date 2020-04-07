<?php
/**
 * @var $this CController
 * @var $form BsActiveForm
 */

$form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'id'=>'department-card-form',
	//'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,		
	),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); 
?>
	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->hiddenField($model,'id_department'); ?>	

	<?php echo $form->textFieldControlGroup($model,'user_fio',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldControlGroup($model,'user_rank',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldControlGroup($model,'user_position',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldControlGroup($model,'user_telephone',array('class'=>'span5','maxlength'=>50)); ?>
	
	<?php echo $form->textAreaControlGroup($model,'user_resp',array('ControlGroups'=>10)); ?>

	<?php echo $form->fileFieldControlGroup($model,'photoFile',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->dropDownListControlGroup($model,'user_level', [0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]); ?>
	
	<div class="form-actions">
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
	</div>

<?php $this->endWidget(); ?>
