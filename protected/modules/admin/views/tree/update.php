<?php
$this->breadcrumbs=array(
	'Структура сайта'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Изменить #<?php echo $model->id; ?> (<?= $model->name ?>)</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>