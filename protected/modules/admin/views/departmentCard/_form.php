<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
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
	
	<?php //echo $form->textFieldRow($model,'id_user',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'user_fio',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'user_rank',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'user_position',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'user_telephone',array('class'=>'span5','maxlength'=>50)); ?>
	
	<?php echo $form->textAreaRow($model,'user_resp',array('class'=>'span5','rows'=>10)); ?>

	<?php echo $form->fileFieldRow($model,'photoFile',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'user_level',array('class'=>'span5')); ?>
	
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',			
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>



	
<?php $this->endWidget(); ?>
