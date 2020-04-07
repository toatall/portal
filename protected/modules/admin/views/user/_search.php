<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	
	<?php echo $form->textFieldControlGroup($model,'username_windows',array('class'=>'span5','maxlength'=>250)); ?>
	
	<?php echo $form->checkBoxControlGroup($model,'blocked'); ?>

	<div class="form-actions">
        <?= BsHtml::submitButton('Поиск', ['class'=>'btn btn-primary']) ?>
	</div>

<?php $this->endWidget(); ?>
