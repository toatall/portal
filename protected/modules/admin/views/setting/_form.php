<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'setting-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'group',array('class'=>'span5','maxlength'=>45)); ?>
    <?php echo $form->dropDownListRow($model,'group',array('general'=>'Общие','path'=>'Расположения')); ?>

	<?php echo $form->textFieldRow($model,'key',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'description',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'value',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'date_create',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
