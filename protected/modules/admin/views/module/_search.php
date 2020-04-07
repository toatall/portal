<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldControlGroup($model,'name',array('maxlength'=>50)); ?>

	<?php echo $form->textFieldControlGroup($model,'description',array('maxlength'=>250)); ?>

	<?php echo $form->textFieldControlGroup($model,'date_create'); ?>

	<?php echo $form->textFieldControlGroup($model,'author'); ?>

    <?= BsHtml::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>

<?php $this->endWidget(); ?>
