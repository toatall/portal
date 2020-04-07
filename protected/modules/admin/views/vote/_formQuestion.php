<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'id'=>'vote-question-form',
	'enableAjaxValidation'=>false,
)); ?>


	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textAreaControlGroup($model,'text_question',array('rows'=>6)); ?>
	
	<div class="form-actions">
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
	</div>

<?php $this->endWidget(); ?>
