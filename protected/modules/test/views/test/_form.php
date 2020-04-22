<?php
/* @var $this TestController */
/* @var $model Test */
/* @var $form BsActiveForm */

$datepickerAsset = new DatepickerAsset();
$datepickerAsset->register();
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'id'=>'test-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

    <?php echo $form->telFieldControlGroup($model,'name', ['maxlength'=>250]) ?>

    <?php echo $form->textFieldControlGroup($model,'date_start', ['class'=>'datepicker']) ?>

    <?php echo $form->textFieldControlGroup($model,'date_end', ['class'=>'datepicker']) ?>

    <?php echo $form->numberFieldControlGroup($model,'count_attempt') ?>

    <?php echo $form->numberFieldControlGroup($model,'count_questions') ?>

    <?php echo $form->textAreaControlGroup($model,'description', ['rows'=>'5']) ?>


	<div class="row buttons">
		<?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->