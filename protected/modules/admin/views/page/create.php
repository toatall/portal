<?php
$this->breadcrumbs=array(
	$modelTree->name=>array('admin', 'idTree'=>$modelTree->id),
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin', 'idTree'=>$modelTree->id), 'icon'=>'user'),
);
?>

<h1>Создать</h1>

<?php echo $this->renderPartial('../news/_form', array('model'=>$model, 'modelTree'=>$modelTree)); ?>