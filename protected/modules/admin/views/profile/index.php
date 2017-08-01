<?php
$this->breadcrumbs=array(
	'Profiles',
);

$this->menu=array(
	array('label'=>'Создать Profile','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Управление Profile','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Profiles</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
