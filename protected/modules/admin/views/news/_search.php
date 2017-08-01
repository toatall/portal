<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'id_tree',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>500)); ?>

	<?php //echo $form->textAreaRow($model,'message1',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php //echo $form->textAreaRow($model,'message2',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'author',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'date_start_pub',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'date_end_pub',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'date_create',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'date_modification',array('class'=>'span5')); ?>

	<?php //echo $form->checkBoxRow($model,'flag_delete'); ?>

	<?php echo $form->checkBoxRow($model,'flag_enable'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Поиск',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
