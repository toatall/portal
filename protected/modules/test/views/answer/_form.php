<?php
/* @var $this AnswerController */
/* @var $model TestAnswer */
/* @var $form BsActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'id'=>'test-answer-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    ),
)); ?>

	<?php echo $form->errorSummary($model); ?>

    <?= $form->textFieldControlGroup($model, 'name') ?>
    <?= $form->fileFieldControlGroup($model, 'file') ?>
    <?php if (!$model->isNewRecord && $model->attach_file): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Файл
        </div>
        <div class="panel-body">
            <?= BsHtml::link($model->attach_file, $model->getAttachUrl($model->attach_file)) ?><br />
            <?= $form->checkBoxControlGroup($model, 'delFile') ?>
        </div>
    </div>
    <?php endif; ?>

    <?= $form->numberFieldControlGroup($model, 'weight') ?>

	<div class="row buttons">
		<?= BsHtml::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->