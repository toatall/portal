<?php
/* @var $this EmailGovermentController */
/* @var $model EmailGoverment */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id'=>'email-goverment-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>true,
)); ?>

    <p class="help-block">Поля помеченные <span class="required">*</span> являются обязательными для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model,'org_name',array('maxlength'=>1000)); ?>
    <?php echo $form->textFieldControlGroup($model,'ruk_name',array('maxlength'=>1000)); ?>
    <?php echo $form->textFieldControlGroup($model,'telephone',array('maxlength'=>200)); ?>
    <?php echo $form->textFieldControlGroup($model,'email',array('maxlength'=>500)); ?>
    <?php echo $form->textFieldControlGroup($model,'post_address',array('maxlength'=>-1)); ?>

    <?php echo BsHtml::submitButton('Сохранить', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)); ?>

<?php $this->endWidget(); ?>
<script type="text/javascript">

    $(document).ready(function() {
        $('#email-goverment-form').on('submit', function (e) {
            e.preventDefault();

            ajaxJSON($(this).attr('action'), {
                'title': '#modal-title',
                'content': '#modal-body'
            }, null, 'post', $(this).serialize());

            return false;
        });
    });

</script>