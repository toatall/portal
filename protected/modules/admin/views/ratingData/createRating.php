<?php
$this->breadcrumbs=array(
	'Рейтинги'=>array('adminRating', 'id'=>$modelRatingMain->id),
	'Создать',
);

$this->menu=array(	
	array('label'=>'Управление','url'=>array('adminRating', 'id'=>$modelRatingMain->id), 'icon'=>'user'),
);
?>

<h1>Создать рейтинг</h1>

<?php echo $this->renderPartial('_formRating', array('model'=>$model)); ?>