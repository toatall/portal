<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'vote-question-form',
	'enableAjaxValidation'=>false,
)); ?>


	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textArea($model,'text_question',array('class'=>'span6', 'rows'=>6)); ?>
	
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
