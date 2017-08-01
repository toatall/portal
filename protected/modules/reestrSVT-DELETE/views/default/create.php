<?php
$this->breadcrumbs=array(
	'Реестр разрешений на приобретение СВТ'=>array('admin'),
	'Создать',
);

?>

<h1>Создать</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>