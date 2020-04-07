<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'id'=>'organization-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
    
    <?php echo $form->textFieldControlGroup($model,'code',array('class'=>'span5','maxlength'=>4)); ?>
    
	<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldControlGroup($model,'sort',array('class'=>'span5')); ?>

	<div class="form-actions">
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
	</div>

<?php $this->endWidget(); ?>
