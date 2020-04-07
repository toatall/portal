<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldControlGroup($model,'id',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldControlGroup($model,'id_tree',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'title',array('class'=>'span5','maxlength'=>500)); ?>

	<?php //echo $form->textAreaControlGroup($model,'message1',array('ControlGroups'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php //echo $form->textAreaControlGroup($model,'message2',array('ControlGroups'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldControlGroup($model,'author',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldControlGroup($model,'date_start_pub',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'date_end_pub',array('class'=>'span5')); ?>

	<?php echo $form->textFieldControlGroup($model,'date_create',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldControlGroup($model,'date_modification',array('class'=>'span5')); ?>

	<?php //echo $form->checkBoxControlGroup($model,'flag_delete'); ?>

	<?php echo $form->checkBoxControlGroup($model,'flag_enable'); ?>

	<div class="form-actions">
        <?= BsHtml::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
	</div>

<?php $this->endWidget(); ?>
