<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('admin'),
	'Создание пользователя',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('admin'), 'icon'=>'list'),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Создание пользователя</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>