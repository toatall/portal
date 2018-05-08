<?php
$this->breadcrumbs=array(
	'Отдел (' . $modelDepartment->concatened . ')' => array('admin', 'idTree'=>$model->id_tree),
	'Управление',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create', 'idTree'=>$model->id_tree), 'icon'=>'asterisk'),	
	array('label'=>'<hr />','type'=>'raw'),
	array('label'=>'Настройка отдела','url'=>array('options', 'id'=>$modelDepartment->id, 'idTree'=>$model->id_tree), 'icon'=>'cog'),
);

if ($modelDepartment->use_card):
	$this->menu = array_merge($this->menu, array(
		array('label'=>'<hr />','type'=>'raw'),
		array('label'=>'Структура отдела','url'=>array('department/updateStructure', 'id'=>$modelDepartment->id), 'icon'=>'list-alt'),
	));
endif;
?>

<h1>Страницы (<?= $modelDepartment->concatened ?>)</h1>
<p>
<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'department-news-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
        array(
            'name'=>'title',
            'value'=>$model->title,
        ),
        'date_create',
        'date_start_pub',
        'date_end_pub',
        array(
            'name'=>'flag_enable',
            'value'=>'$data->flag_enable?"Да":"Нет"',
            'filter'=>array('0'=>'Нет', '1'=>'Да'),
        ),
        'author',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'buttons'=>array(
				'view'=>array(
					'url'=>'Yii::app()->createUrl("admin/departmentData/view", array("id"=>$data->id,"idTree"=>$data->id_tree))',
				),
				'update'=>array(
					'url'=>'Yii::app()->createUrl("admin/departmentData/update", array("id"=>$data->id,"idTree"=>$data->id_tree))',
				),
				'delete'=>array(
					'url'=>'Yii::app()->createUrl("admin/departmentData/delete", array("id"=>$data->id,"idTree"=>$data->id_tree))',
				),
			),
		),
	),
)); ?>
