<?php
    /* @var $model RegEcr */
?>

<?php
$this->breadcrumbs=array(
    'Анкетирование по ГР'=>array('admin'),
    'Управление',
);

$this->menu=array(
    array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
);

?>

<h1>Анкетирование по ГР</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
    'id'=>'regecr-main-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'id',        
        [
            'filter' => $model->getDropDownIfns(),
            'name' => 'code_org',
        ], 
        'date_reg',
//        [
//            'name' => 'date_reg',
//            'value' => 'date("d.m.Y", strtotime($data->date_reg))',
//        ],
        'count_create',
        'count_vote',
        'avg_eval_a_1_1',
        'avg_eval_a_1_2',
        'avg_eval_a_1_3',
//        [
//            'name' => 'user.fio',
//            'header' => 'Автор',
//        ],
//        'date_create',
//        'date_update',
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
        ),
    ),
)); ?>

