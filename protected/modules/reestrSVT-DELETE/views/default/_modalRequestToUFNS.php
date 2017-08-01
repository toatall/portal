<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'group-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textAreaRow($model,'work_spares',
	array('class'=>'span5','style'=>'height:100px;')); ?>	


<div class="modal-footer">
<?php 
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'label'=>'OK',
	)); 
?>
<?php 
	$this->widget('bootstrap.widgets.TbButton', array(		
		'label'=>'Отмена',
		'htmlOptions'=>array('data-dismiss'=>'modal'),
	)); 
?>
</div>

<?php $this->endWidget(); ?>