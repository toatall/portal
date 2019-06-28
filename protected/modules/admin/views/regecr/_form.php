<?php
    /* @var $form TbActiveForm */
    /* @var $model RegEcr */
?>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'regecr-main-form',
    'enableAjaxValidation'=>false,
)); ?>


<?php    
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
    Yii::app()->getClientScript()->registerCssFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
?>

    <p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->dropDownListRow($model, 'code_org', $model->getDropDownIfns(),array('class'=>'span8')); ?>

    <?php echo $form->textFieldRow($model,'date_reg', array('class'=>'span3')); ?>
    
    <?php echo $form->textFieldRow($model,'count_create', array('class'=>'span3')); ?>

    <?php echo $form->textFieldRow($model,'count_vote', array('class'=>'span3')); ?>
    
    <?php echo $form->textFieldRow($model,'avg_eval_a_1_1', array('class'=>'span3')); ?>
    
    <?php echo $form->textFieldRow($model,'avg_eval_a_1_2', array('class'=>'span3')); ?>
    
    <?php echo $form->textFieldRow($model,'avg_eval_a_1_3', array('class'=>'span3')); ?>
    
    <br /><br />
    <div class="alert alert-info">
        <h4>Описание</h4><hr />
        <b><?= $model->getAttributeLabel('date_reg') ?></b> - Дата регистрации<br />
        <b><?= $model->getAttributeLabel('count_create') ?></b> - Количество вновь созданных ООО<br />
        <b><?= $model->getAttributeLabel('count_vote') ?></b> - Количество опрошенных представителей вновь созданных ООО (1 представитель в отношении 1 вновь созданного ООО)<br />
        <b><?= $model->getAttributeLabel('avg_eval_a_1_1') ?></b> - Средняя оценка респондентами по показателю А 1.1 "Среднее время регистрации, юридических лиц", дней (среднее арифметическое от общего количества опрошенных респондентов)<br />
        <b><?= $model->getAttributeLabel('avg_eval_a_1_2') ?></b> - Средняя оценка респондентами по показателю А 1.2 "Среднее количество процедур, необходимых для регистрации юридических лиц", штук (среднее арифметическое от общего количества опрошенных респондентов)<br />
        <b><?= $model->getAttributeLabel('avg_eval_a_1_3') ?></b> - Средняя оценка респондентами по показателю А 1.3 "Оценка деятельности органов власти по регистрации, юридических лиц", баллов (среднее арифметическое от общего количества опрошенных респондентов)<br />
    </div>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'type'=>'primary',
            'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
        )); ?>
    </div>

<?php $this->endWidget(); ?>

<script type="text/javascript">

    jQuery('#<?php echo CHtml::activeId($model, 'date_reg'); ?>').datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });        
    
</script>
