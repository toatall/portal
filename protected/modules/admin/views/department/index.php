<?php
$this->breadcrumbs=array(
	'Отделы'=>array('admin'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Отделы','url'=>array('admin'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
);

?>
<h1>Отделы</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'department-grid',
	'dataProvider'=>$model->search(true),
	'columns'=>array(		
		'department_index',
		'department_name',

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{view}',
			'buttons'=>array(
				'view'=>array(
					'label'=>'Просмотр',
					'url'=>'Yii::app()->createUrl("admin/department/updateStructure", ["id"=>$data->id])',
					'options'=>['class'=>'btn btn-primary'],
				),		
			),
		),
	),
)); ?>
