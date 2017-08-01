<?php
$this->breadcrumbs=array(
	'Новости'=>array('admin', 'idTree'=>$modelTree->id),
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление новостями','url'=>array('admin', 'idTree'=>$modelTree->id), 'icon'=>'user'),
);
?>

<h1>Создать новость</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'idTree'=>$modelTree->id, 'modelTree'=>$modelTree)); ?>