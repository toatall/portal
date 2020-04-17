<?php
/* @var $this ZgTemplateController */
/* @var $model Template */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <?php echo $form->textFieldControlGroup($model,'id'); ?>
    <?php echo $form->textFieldControlGroup($model,'kind',array('maxlength'=>1000)); ?>
    <?php echo $form->textFieldControlGroup($model,'description'); ?>
    <?php echo $form->textFieldControlGroup($model,'date_create'); ?>
    <?php echo $form->textFieldControlGroup($model,'date_update'); ?>
    <?php echo $form->textFieldControlGroup($model,'author',array('maxlength'=>250)); ?>

    <div class="form-actions">
        <?php echo BsHtml::submitButton('Поиск',  array('color' => BsHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

<?php $this->endWidget(); ?>
