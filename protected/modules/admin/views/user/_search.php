<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'username',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'username_windows',array('class'=>'span5','maxlength'=>250)); ?>
	
	<?php echo $form->checkBoxRow($model,'blocked'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Поиск',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
