<?php
$this->breadcrumbs=array(
	'Справочник НО'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Создать НО</h1>

<?php $model->enabled=1; ?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>