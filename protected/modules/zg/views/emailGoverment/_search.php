<?php
/* @var $this EmailGovermentController */
/* @var $model EmailGoverment */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <?php echo $form->textFieldControlGroup($model,'id'); ?>
    <?php echo $form->textFieldControlGroup($model,'org_name',array('maxlength'=>1000)); ?>
    <?php echo $form->textFieldControlGroup($model,'ruk_name',array('maxlength'=>1000)); ?>
    <?php echo $form->textFieldControlGroup($model,'telephone',array('maxlength'=>200)); ?>
    <?php echo $form->textFieldControlGroup($model,'email',array('maxlength'=>500)); ?>
    <?php echo $form->textFieldControlGroup($model,'post_address',array('maxlength'=>-1)); ?>
    <?php echo $form->textFieldControlGroup($model,'date_create'); ?>
    <?php echo $form->textFieldControlGroup($model,'date_update'); ?>
    <?php echo $form->textFieldControlGroup($model,'author',array('maxlength'=>250)); ?>

    <div class="form-actions">
        <?php echo BsHtml::submitButton('Search',  array('color' => BsHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

<?php $this->endWidget(); ?>
