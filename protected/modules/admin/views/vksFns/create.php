<?php
$this->breadcrumbs=array(
	'ВКС ФНС'=>array('admin','idTree'=>$idTree),
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin','idTree'=>$idTree), 'icon'=>'user'),
);
?>

<h1>Создать ВКС ФНС</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'idTree'=>$idTree)); ?>