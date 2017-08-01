<?php
$this->breadcrumbs=array(
	$modelTree->name=>array('admin', 'idTree'=>$modelTree->id),
	$model->title=>array('view','id'=>$model->id, 'idTree'=>$modelTree->id),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create', 'idTree'=>$modelTree->id), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id, 'idTree'=>$modelTree->id), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('admin', 'idTree'=>$modelTree->id), 'icon'=>'user'),
);
?>

<h1>Изменить новость #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('../news/_form',array('model'=>$model, 'modelTree'=>$modelTree,)); ?>