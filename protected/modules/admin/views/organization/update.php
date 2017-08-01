<?php
$this->breadcrumbs=array(
	'Организации'=>array('index'),
	$model->name=>array('view','code'=>$model->code),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','code'=>$model->code), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Изменить организацию #<?php echo $model->code; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>