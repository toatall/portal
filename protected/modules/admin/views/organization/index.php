<?php
$this->breadcrumbs=array(
	'Organizations',
);

$this->menu=array(
	array('label'=>'Создать Organization','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Управление Organization','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Organizations</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
