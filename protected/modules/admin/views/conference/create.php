<?php

$title = $model->typeName;	

$this->breadcrumbs=array(
	$title=>array('admin'), 
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление',
		'url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1><?= $title ?></h1>

<?php echo $this->renderPartial('/conference/_form', 
	array('model'=>$model)); ?>