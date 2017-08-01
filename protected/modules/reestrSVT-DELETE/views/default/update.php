<?php
$this->breadcrumbs=array(
	'Реестр разрешений на приобретение СВТ'=>array('admin'),
	'#'.$model->id=>array('view','id'=>$model->id),
	'Изменить',
);

?>

<h1>Изменить заявку #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>