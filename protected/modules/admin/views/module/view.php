<?php
$this->breadcrumbs=array(
	'Модули'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->name), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->name),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Просмотр <?php echo $model->name; ?></h1>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'description',		
        array(
            'name'=>'date_create',
            'value'=>DateHelper::explodeDateTime($model->date_create),
        ),
		'author',
        array(
            'name'=>'only_one',
            'value'=>($model->only_one ? 'Да' : 'Нет'),
        ),
        array(
            'name'=>'log_change',
            'type'=>'raw',
            'value'=>$model->logChangeText,
        ),        
	),
)); ?>

