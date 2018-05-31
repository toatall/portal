<?php
$this->breadcrumbs=array(
	'Vote Mains'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Изменить',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Изменить # <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>