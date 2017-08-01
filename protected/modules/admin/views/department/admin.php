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

<h1>Управление Отделами</h1>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'department-grid',
	'dataProvider'=>$model->search(true),
	'filter'=>$model,
	'columns'=>array(
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{struct}',
			'buttons'=>array(	
				'struct'=>array(
					'label'=>'Структура',
					'url'=>'Yii::app()->createUrl("admin/department/updateStructure", array("id"=>$data->id))',
					'options'=>array('class'=>'btn btn-primary'),
				),	
			),
		),
		'id',
		'id_tree',
		'id_organization',
		'department_index',
		'department_name',
		'date_create',
		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',			
		),
	),
)); ?>
