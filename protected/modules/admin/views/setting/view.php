<?php
$this->breadcrumbs=array(
	'Settings'=>array('index'),
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

<h1>Просмотр Setting #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'group',
		'key',
		'description',
		'value',
        array(
		  'name'=>'date_create',
          'value'=>date('d.m.Y H:i:s', strtotime($model->date_create)),
        ),
        array(
		  'name'=>'date_modification',
          'value'=>($model->date_modification!='') ? 
            date('d.m.Y H:i:s', strtotime($model->date_modification)) : null,
        ),
        'author',
	),
)); ?>
