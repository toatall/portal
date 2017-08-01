<?php
$this->breadcrumbs=array(
	'Обновление СЭОД'=>array('admin','idTree'=>$idTree),
	$model->name,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create','idTree'=>$idTree), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id,'idTree'=>$idTree), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#','linkOptions'=>array(
        'submit'=>array('delete','id'=>$model->id,'idTree'=>$idTree),
        'confirm'=>'Вы уверены что хотите удалить запись?'), 'icon'=>'trash'),
	array('label'=>'Управление','url'=>array('admin','idTree'=>$idTree), 'icon'=>'user'),
);
?>

<h1>Просмотр записи #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'support',
		'name',
		'path',
        array(
            'name'=>'date_update',
            'value'=>date('d.m.Y', strtotime($model->date_update)),
        ),		
		array(
            'name'=>'date_create',
            'value'=>date('d.m.Y H:i:s', strtotime($model->date_create)),
        ),
		array(
            'name'=>'log_change',
            'type'=>'raw',
            'value'=>LogChange::getLog($model->log_change),
        ),
	),
)); ?>
