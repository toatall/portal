<?php

$this->breadcrumbs = array(
    'Анкетирование по ГР (Детализация)',
);

?>

<style type="text/css">	
    table th 
    {
        background: #eaeaea;
    }	
</style>

<div class="content content-color">
<h1>Анкетирование по ГР (Детализация)</h1>
<hr />
<?= CHtml::link('Статистика', ['regecr/index'], ['class'=>'btn btn-default']) ?>&nbsp;&nbsp;
<?= CHtml::link('В виде графика', ['regecr/chart'], ['class'=>'btn btn-default']) ?>
<hr />
<div class="alert alert-info">
    <h4>Фильтр</h4><br />
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
        'id'=>'search-form',
    )); ?>
    
    <?php echo $form->textField($model,'date1',array('class'=>'span2','data-type'=>'date')); ?>
    <?php echo $form->textField($model,'date2',array('class'=>'span2','data-type'=>'date')); ?>
    
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Поиск',
        'htmlOptions'=>[
            'style'=>'margin-top:-10px;',
        ],
    )); ?>

    <?php $this->endWidget(); ?>
</div>

<?php
Yii::app()->clientScript->registerScript('search', "   
    $('#search-form').submit(function(){
        $.fn.yiiGridView.update('regecr-grid', {
            data: $(this).serialize()
        });
        return false;
    });
    jQuery('input[data-type=\"date\"]').datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });   
");
?>
<?php    
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
    Yii::app()->getClientScript()->registerCssFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'regecr-grid',	
	'dataProvider'=>$model->search(),
	'filter'=>$model,     	
	'columns'=>array(
            [
                'filter' => $model->getDropDownIfns(),
                'name' => 'code_org',
            ], 
            [
                'name' => 'date_reg',
                'filter' => '',
            ],           
            'count_create',
            'count_vote',
            [
                'name' => 'avg_eval_a_1_1',
                'header' => 'Средняя оценка А 1.1 <span class="badge badge-info" data-toggle="popover" data-original-title=\'Средняя оценка респондентами по показателю А 1.1 "Среднее время регистрации, юридических лиц", дней (среднее арифметическое от общего количества опрошенных респондентов)\'><i class="fa fa-info"></i></span>',
                'sortable' => false,
            ],
            [
                'name' => 'avg_eval_a_1_2',
                'header' => 'Средняя оценка А 1.2 <span class="badge badge-info" data-toggle="popover" data-original-title=\'Средняя оценка респондентами по показателю А 1.2 "Среднее количество процедур, необходимых для регистрации юридических лиц", штук (среднее арифметическое от общего количества опрошенных респондентов)\'><i class="fa fa-info"></i></span>',
                'sortable' => false,
            ],
            [
                'name' => 'avg_eval_a_1_3',
                'header' => 'Средняя оценка А 1.3 <span class="badge badge-info" data-toggle="popover" data-original-title=\'Средняя оценка респондентами по показателю А 1.3 "Оценка деятельности органов власти по регистрации, юридических лиц", баллов (среднее арифметическое от общего количества опрошенных респондентов)\'><i class="fa fa-info"></i></span>',
                'sortable' => false,
            ],
	),
	'pager'=>array(	
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast'=>true,
	),
)); ?>
</div>