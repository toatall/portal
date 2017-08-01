<?php
$this->breadcrumbs=array(
	'Update Eods',
);

$this->menu=array(
	array('label'=>'Create UpdateEod','url'=>array('create')),
	array('label'=>'Manage UpdateEod','url'=>array('admin')),
);
?>

<h1>Update Eods</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
