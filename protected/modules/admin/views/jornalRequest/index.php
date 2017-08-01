<?php
$this->breadcrumbs=array(
	'Jornal Requests',
);

$this->menu=array(
	array('label'=>'Create JornalRequest','url'=>array('create')),
	array('label'=>'Manage JornalRequest','url'=>array('admin')),
);
?>

<h1>Jornal Requests</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
