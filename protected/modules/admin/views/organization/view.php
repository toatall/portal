<?php
$this->breadcrumbs=array(
	'Организации'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','code'=>$model->code), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->code),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Просмотр организации #<?php echo $model->code; ?></h1>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes' => [
		'code',
		'name',
		'sort',
		'date_create',
		'date_modification',
	],
)); ?>
