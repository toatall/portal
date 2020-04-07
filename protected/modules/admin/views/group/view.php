<?php
$this->breadcrumbs=array(
	'Группы'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#','linkOptions'=>array(
        'submit'=>array('delete','id'=>$model->id),
        'confirm'=>'Вы уверены что хотите удалить "'.$model->name.'"?'),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Группа #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'date_create',
		'date_modification',
	),
)); ?>
