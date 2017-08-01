<?php
$this->breadcrumbs=array(
	'Журнал заявок'=>array('admin','idTree'=>$idTree),
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin','idTree'=>$idTree)),
);
?>

<h1>Создать заявку</h1>

<?php echo $this->renderPartial('_form', array(
    'model'=>$model,
    'idTree'=>$idTree,    
)); ?>