<?php
/* @var $this ZgTemplateController */
/* @var $model Template */
/* @var $form BSActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
    'htmlOptions' => [
        'id'=>'form-search-template',
    ],
)); ?>
    <?php echo $form->dropDownListControlGroup($model,'kind', array_merge(['' => '- все виды обращений -'], $model->listKinds())); ?>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $('#<?= CHtml::activeId($model, 'kind') ?>').on('change', function () {
       $('#form-search-template').submit();
    });
</script>
