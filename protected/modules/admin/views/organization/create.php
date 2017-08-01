<?php
$this->breadcrumbs=array(
	'Организации'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Создать организацию</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>