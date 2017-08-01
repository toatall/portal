<?php
$this->breadcrumbs=array(
	'Система электронных заявок'=>array('admin'),
	'#' . $model->id=>array('view','id'=>$model->id),
	'Изменить',
);

?>

<h1>Изменить #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>