<?php
$this->breadcrumbs=array(
	'Телефонный справочник'=>array('admin', 'idTree'=>$idTree),
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление', 'url'=>array('admin', 'idTree'=>$idTree), 'icon'=>'user'),
);
?>

<h1>Создать телефонный справочник</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'idTree'=>$idTree)); ?>