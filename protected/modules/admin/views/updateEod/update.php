<?php
$this->breadcrumbs=array(
	'Обновление СЭОД'=>array('admin','idTree'=>$idTree),
	$model->name=>array('view','id'=>$model->id,'idTree'=>$idTree),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create','idTree'=>$idTree), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id,'idTree'=>$idTree), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('admin','idTree'=>$idTree), 'icon'=>'user'),
);
?>

<h1>Изменить запись #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model,'idTree'=>$idTree)); ?>