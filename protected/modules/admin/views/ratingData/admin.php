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

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'rating-main-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
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
		//'id_tree',
		'name',
		'order_asc',
		'date_create',
		/*
		'log_change',		
		'author',
		'note',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',			
			/*'template'=>'{rating} {update} {view} {delete}',
			'buttons'=>array(
				'rating'=>array(
					'url'=> function($data) {
						return $data->id;
					},
				),
			),*/
		
		),
	),
)); ?>
