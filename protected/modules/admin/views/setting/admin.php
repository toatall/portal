<?php
$this->breadcrumbs=array(
	'Settings'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список Setting','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать Setting','url'=>array('create'), 'icon'=>'asterisk'),
);

?>

<h1>Настройки</h1>




<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'setting-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'group',
		'key',
		'description',
		'value',
		'date_create',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
