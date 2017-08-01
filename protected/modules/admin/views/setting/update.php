<?php
$this->breadcrumbs=array(
	'Settings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Список Setting','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать Setting','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Просмотр Setting','url'=>array('view','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Управление Setting','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Изменить Setting <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>