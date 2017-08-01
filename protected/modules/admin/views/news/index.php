<?php
$this->breadcrumbs=array(
	'News',
);

$this->menu=array(
	array('label'=>'Создать News','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Управление News','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>News</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
