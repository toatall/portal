<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldControlGroup($model,'code',array('class'=>'span5','maxlength'=>4)); ?>

	<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldControlGroup($model,'sort',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'date_create',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'date_edit',array('class'=>'span5')); ?>

	<div class="form-actions">
        <?= BsHtml::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
	</div>

<?php $this->endWidget(); ?>
