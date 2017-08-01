<?php

$title = $model->typeName;

$this->breadcrumbs=array(
	$title=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'Изменение',
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Изменить #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('/conference/_form',array('model'=>$model)); ?>