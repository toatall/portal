<?php
$this->breadcrumbs=array(
	'Голосование'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
    array('label'=>'Управление вопросами', 'url'=>array('adminQuestion', 'idMain'=>$model->id), 'icon'=>'user'),
);
?>

<h1>Просмотр записи #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'date_start',
		'date_end',
		'organizations',
		'multi_answer',
	    'description',
		'date_create',
		'date_edit',
		array(
		    'name'=>'log_change',
		    'type'=>'raw',
		    'value'=>Log::getLog($model->log_change),
		),
	),
)); ?>
