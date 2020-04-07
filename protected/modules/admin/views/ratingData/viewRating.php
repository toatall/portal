<?php
$this->breadcrumbs=array(
	'Рейтинги'=>array('adminRating', 'id'=>$model->ratingMain->id),
		$model->periodName . ' ' . $model->rating_year,
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('createRating', 'id'=>$model->ratingMain->id), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('updateRating','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('deleteRating','id'=>$model->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin', 'id'=>$model->ratingMain->id), 'icon'=>'user'),
);
?>

<h1>Просмотр рейтинга #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',		
		'rating_year',
		'periodName',
		'date_create',
		'log_change',
		'author',
		'note',		
		'fileView:RAW'
	),
)); ?>
