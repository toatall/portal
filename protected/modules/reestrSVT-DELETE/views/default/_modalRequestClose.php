<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'group-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textAreaRow($model,'text_close',
	array('class'=>'span5','style'=>'height:100px;')); ?>

<?php if (Yii::app()->user->inRole(['admin', 'reestrSVT_FKU'])): ?>
	<?php echo $form->checkboxRow($model,'solved_fku'); ?>
<?php endif; ?>


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