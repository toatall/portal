<?php
$this->breadcrumbs=array(
	'Справочник НО'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->code), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->code),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?'
        ), 
        'icon'=>'trash'
    ),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Просмотр НО #<?php echo $model->code; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'code',
		'name',	
		'date_create',
		'date_modification',
		array(
            'name'=>'enabled',
            'value'=>($model->enabled)?'Да':'Нет'
        ),
	),
)); ?>
