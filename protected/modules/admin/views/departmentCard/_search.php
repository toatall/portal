<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'id_department',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'id_user',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'user_fio',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'user_rank',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'user_position',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'user_telephone',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'user_photo',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'user_level',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'sort_index',array('class'=>'span5')); ?>

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
