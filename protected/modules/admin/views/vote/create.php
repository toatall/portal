<?php
$this->breadcrumbs=array(
	'Голосование'=>array('admin'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Создать</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>