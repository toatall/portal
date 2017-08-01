<?php
$this->breadcrumbs=array(
	'Система электронных заявок'=>array('admin'),
	'#'.$model->id,
);

?>

<h1>Просмотр Sez #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		/*'id',*/
		'codeNo.FullName',
		'TypeSezText',
		'statusText',
		'message',
		/*'id_author',
		'date_create',
		'date_edit',
		'log_change',*/
	),
)); ?>
