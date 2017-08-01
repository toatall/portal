<?php
$this->breadcrumbs=array(
	'Справочник НО'=>array('index'),
	$model->name=>array('view','id'=>$model->code),
	'Update',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->code), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Изменить НО <?php echo $model->code; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>