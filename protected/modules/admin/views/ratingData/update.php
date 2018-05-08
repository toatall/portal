<?php

$this->breadcrumbs=array(
	'Рейтинги'=>array('admin','idTree'=>$model->id_tree),
	$model->name=>array('view','id'=>$model->id),
	'Изменить',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create', 'idTree'=>$model->id_tree), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('admin','idTree'=>$model->id_tree), 'icon'=>'user'),
);
?>

<h1>Изменить #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>