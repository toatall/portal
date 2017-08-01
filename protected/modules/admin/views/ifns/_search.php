<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>  
    
    <?php $model->enabled = 1; ?>

	<?php echo $form->textFieldRow($model,'code',array('class'=>'span5','maxlength'=>4)); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'date_create',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'date_modification',array('class'=>'span5')); ?>

	<?php echo $form->checkBoxRow($model,'enabled'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Поиск',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
