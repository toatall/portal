<?php
$this->breadcrumbs=array(
	'Система электронных заявок'=>array('admin'),
	'Данные заявки',
);

?>

<h1>Данные заявки</h1>


<?php echo $this->renderPartial('../sezData/_form', array('model'=>$modelData, 'modelSez'=>$model)); ?>