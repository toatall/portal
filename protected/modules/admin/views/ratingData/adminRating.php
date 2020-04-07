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

<?php $this->widget('bootstrap.widgets.BsGridView',array(
	'id'=>'rating-data-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
			
		'id',
		'rating_year',
		'periodName',
		'date_create',
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
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
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
