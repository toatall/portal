<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Обращение';
$this->breadcrumbs=array(
	'Обращение',
);
?>
<div class="content content-color">
<h1>Обращение</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>
<?= CHtml::link('На главную', array('site/index'), array('class'=>'btn btn-success')) ?>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128,'class'=>'span5')); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('class'=>'span5','rows'=>10)); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Отпавить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>
<script type="text/javascript">
	$('#contact-form').on('submit', function() {
		$('#<?= CHtml::activeId($model, 'body') ?>').val($('#contactDiv').html());
	});
</script>
<?php endif; ?>