<?php
$this->breadcrumbs=array(
		'Рейтинги'=>array('admin', 'idTree'=>$modelRatingMain->id_tree),
		'Управление "' . $modelRatingMain->name . '"',
);

$this->menu=array(
		array('label'=>'Создать','url'=>array('createRating', 'id'=>$modelRatingMain->id), 'icon'=>'asterisk'),
);

?>

<h1>Управление рейтингом "<?= $modelRatingMain->name ?>"</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'rating-data-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
			
		'id',
		//'id_tree',
		'rating_year',
		'ratingPeriodDescription',
		'date_create',
		/*
		'log_change',		
		'author',
		'note',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'buttons'=>array(
				'view'=>array(
					'url'=>function($data) { return Yii::app()->controller->createUrl('/admin/ratingData/viewRating',array('id'=>$data->id));  },		
				),	
				'update'=>array(
					'url'=>function($data) { return Yii::app()->controller->createUrl('/admin/ratingData/updateRating',array('id'=>$data->id));  },
				),
				'delete'=>array(
					'url'=>function($data) { return Yii::app()->controller->createUrl('/admin/ratingData/deleteRating',array('id'=>$data->id));  },
				),
			),
		),
	),
)); ?>
