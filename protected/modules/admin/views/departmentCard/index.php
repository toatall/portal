<?php
throw new CHttpException(410, 'Ресурс удален');
$this->breadcrumbs=array(
	'Department Cards',
);

$this->menu=array(
	array('label'=>'Создать DepartmentCard','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Управление DepartmentCard','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Department Cards</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
