<?php
/**
 * @var $this CController
 * @var $form BsActiveForm
 */


$this->breadcrumbs = array(
    'Анкетирование по ГР (Детализация)',
);

?>

<div class="content content-color">
<h1>Анкетирование по ГР (Детализация)</h1>
<hr />
<?= CHtml::link('Статистика', ['regecr/index'], ['class'=>'btn btn-default']) ?>&nbsp;&nbsp;
<?= CHtml::link('В виде графика', ['regecr/chart'], ['class'=>'btn btn-default']) ?>
<hr />
<div class="alert alert-info">
    <div class="panel-body">

        <?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
            'action'=>Yii::app()->createUrl($this->route),
            'method'=>'get',
                'id'=>'search-form',
            )); ?>

        <div class="col-sm-3">
            <?php echo $form->textField($model,'date1',[
                'class' => 'datepicker',
                'placeholder'=>'Поиск по дате от ...',
                'prepend'=>'<i class="glyphicon glyphicon-calendar kv-dp-icon"></i>',
            ]); ?>
        </div>

        <div class="col-sm-3">
            <?php echo $form->textField($model,'date2',[
                'data-type'=>'date',
                'placeholder'=>'Поиск по дате от ...',
                'prepend'=>'<i class="glyphicon glyphicon-calendar kv-dp-icon"></i>',
            ]); ?>
        </div>

        <?= BsHtml::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>

        <?php $this->endWidget(); ?>

    </div>
</div>

<?php

    $assetDatepicker = new DatepickerAsset();
    $assetDatepicker->register();
?>

<?php $this->widget('bootstrap.widgets.BsGridView',array(
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
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
</div>