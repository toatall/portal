<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldControlGroup($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldControlGroup($model,'date_start',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'date_end',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'organizations',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->checkBoxControlGroup($model,'multi_answer'); ?>

	<?php echo $form->textFieldControlGroup($model,'date_create',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'date_edit',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'log_change',array('class'=>'span5','maxlength'=>-1)); ?>

	<div class="form-actions">
        <?= BsHtml::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
	</div>

<?php $this->endWidget(); ?>
