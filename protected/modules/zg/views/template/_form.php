<?php
/* @var $this ZgTemplateController */
/* @var $model Template */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id'=>'template-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>true,
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    ),
)); ?>

    <p class="help-block">Поля помеченные <span class="required">*</span> являются обязательными для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->dropDownListControlGroup($model,'kind', $model->listKinds()); ?>
    <?php echo $form->textAreaControlGroup($model,'description',['rows'=>5]); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Загрузка файлов</h4>
        </div>
        <div class="panel-body">
        <?php
            $this->widget('CMultiFileUpload', array(
                'name'=>'files',
                'accept'=>'*',
                'duplicate'=>'Файл уже выбран!',
                'remove'=>'<i class="glyphicon glyphicon-remove text-danger"></i>',
            ));
        ?>
        <?php

        $files = $model->getListFiles();
        if (!$model->isNewRecord && count($files) > 0)
        {
            ?>
            <hr/>
            Отметьте файлы для удаления:<br/>
            <?php
            echo CHtml::checkBoxList('Template[deleteFile][]', '',
                $files,
                array('labelOptions' => array('style' => 'display:inline;'))
            );
        }
        ?>
        </div>
    </div>

    <?php echo BsHtml::submitButton('Сохранить', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)); ?>

<?php $this->endWidget(); ?>
<script type="text/javascript">

    /*
    $(document).ready(function() {
        $('#template-form').on('submit', function (e) {
            e.preventDefault();

            ajaxJSON($(this).attr('action'), {
                'title': '#modal-title',
                'content': '#modal-body'
            }, null, 'post', new FormData(this));

            return false;
        });
    });
    */

</script>