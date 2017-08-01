<?php
/* @var $this TelephoneController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Telephones',
);

$this->menu=array(
	array('label'=>'Create Telephone', 'url'=>array('create')),
	array('label'=>'Manage Telephone', 'url'=>array('admin')),
);
?>

<h1>Telephones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
