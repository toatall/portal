<?php
$this->breadcrumbs=array(
	'Система электронных заявок'=>array('admin'),
	'Создать',
);

?>

<h1>Создать заявку</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>