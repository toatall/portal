<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'code_no',array('class'=>'span5','maxlength'=>5)); ?>

	<?php echo $form->textFieldRow($model,'date_fault',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'date_appeal_fku',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'number_appeal_fku',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'date_appeal_fku_ufns',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'device_fault',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'number_inventary',array('class'=>'span5','maxlength'=>30)); ?>

	<?php echo $form->textAreaRow($model,'description_fault',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'work_spares',array('class'=>'span5','maxlength'=>1000)); ?>

	<?php echo $form->textFieldRow($model,'date_appeal_sto',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'numner_appeal_sto',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'date_execution',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'date_close',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'date_create',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'date_edit',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'log_change',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Поиск',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
