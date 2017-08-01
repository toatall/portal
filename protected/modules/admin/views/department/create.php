<?php
$this->breadcrumbs=array(
	'Отделы'=>array('admin'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Создать отдел</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>