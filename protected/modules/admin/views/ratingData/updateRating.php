<?php
$this->breadcrumbs=array(
	'Рейтинги'=>array('adminRating', 'id'=>$model->ratingMain->id),
	$model->periodName . ' ' . $model->rating_year=>array('viewRating','id'=>$model->id),
	'Изменить',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('createRating', 'id'=>$model->ratingMain->id), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('viewRating','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('adminRating','id'=>$model->ratingMain->id), 'icon'=>'user'),
);
?>

<h1>Изменить #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_formRating',array('model'=>$model)); ?>