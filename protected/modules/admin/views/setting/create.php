<?php
$this->breadcrumbs=array(
	'Settings'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список Setting','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Управление Setting','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Создать Setting</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>