<?php

$title = $model->typeName;

$this->breadcrumbs=array(
	$title=>array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить', 'url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#', 'icon'=>'trash',
        'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
        	'confirm'=>'Вы уверены что хотите удалить "'.$model->theme.'"?')),
	array('label'=>'Управление', 'url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1><?= $model->typeName ?> #<?php echo $model->id; ?></h1>


<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes'=>$model->attrForView,
)); ?>
