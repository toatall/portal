<?php
$this->breadcrumbs=array(
	'Рейтинги'=>array('admin', 'idTree'=>$modelTree->id),
	'Управление',
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create', 'idTree'=>$modelTree->id), 'icon'=>'asterisk'),
);

?>

<h1>Управление рейтингами</h1>

<?php $this->widget('bootstrap.widgets.BsGridView',array(
	'id'=>'rating-main-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
			'template'=>'{rating_data}',
			'buttons'=>array(
				'rating_data'=>array(
					'label'=>'Рейтинги',
					'url'=>function($data) { return Yii::app()->createUrl("admin/ratingData/adminRating", array("id"=>$data->id)); },
					'options'=>array('class'=>'btn btn-primary'),
				),
			),
		),
			
		'id',
		'name',
		'order_asc',
		'date_create',		
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
