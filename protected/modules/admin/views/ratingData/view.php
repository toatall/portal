<?php
$this->breadcrumbs=array(
	'Рейтинги'=>array('admin','idTree'=>$model->id_tree),
	$model->name,
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create', 'idTree'=>$model->id_tree), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin', 'idTree'=>$model->id_tree), 'icon'=>'user'),
);
?>

<h1>Просмотр Рейтинга #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',		
		'name',
		'order_asc',
		'date_create',
		'log_change',
		'author',
		'note',		
	),
)); ?>
