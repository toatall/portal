<?php
$this->breadcrumbs=array(
    'Голосование'=>array('admin'),
    $modelMain->name=>array('adminQuestion','idMain'=>$model->id_main),
	$model->id,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('createQuestion', 'idMain'=>$model->id_main), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('updateQuestion','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('deleteQuestion','id'=>$model->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('adminQuestion', 'idMain'=>$model->id_main), 'icon'=>'user'),
);
?>

<h1>Просмотр записи #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'text_question',
		'date_create',
		'date_edit',
		array(
		    'name'=>'log_change',
		    'type'=>'raw',
		    'value'=>Log::getLog($model->log_change),
		),
	),
)); ?>
