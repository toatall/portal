<?php
$this->breadcrumbs=array(
	'Отделы'=>array('admin'),
	$model->id,
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
);
?>

<h1>Просмотр отдела #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_tree',
		'id_organization',
		'department_index',
		'department_name',
		'date_create',
		'date_edit',
		'author',
		'log_change',
	),
)); ?>
