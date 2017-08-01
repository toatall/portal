<?php
$this->breadcrumbs=array(
	'Profiles'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список Profile','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать Profile','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить Profile','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить Profile','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление Profile','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Просмотр Profile #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_user',
		'username',
		'telephone',
		'telephone_ip',
		'name',
		'post',
		'rank',
		'photo_file',
		'aubout',
		'status',
		'date_create',
		'date_edit',
		'log_change',
	),
)); ?>
