<?php
$this->breadcrumbs=array(
	'Рейтинги'=>array('admin', 'idTree'=>$modelTree->id),
	'Создать',
);

$this->menu=array(	
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Создать рейтинг</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>