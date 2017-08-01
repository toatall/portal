<?php

$this->breadcrumbs=array(
	'Пользователи'=>array('admin'),
	$model->username_windows=>array('view','id'=>$model->id),
	'Изменение',
);


$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Изменить профиль','url'=>array('profile/update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Изменение пользователя #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>