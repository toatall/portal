<?php
$this->breadcrumbs=array(
	'Отделы' => ['/admin/department/admin'],
	'Структура отдела ' . $model->department->concatened => 
		['/admin/department/updateStructure', 'id'=>$model->id_department],
	'#' . $model->id,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create', 'idDepartment'=>$model->id_department), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('/admin/department/updateStructure', 
		'id'=>$model->id_department), 'icon'=>'user'),
);
?>

<h1>Просмотр карточки сотрудника #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes'=>array(		
		[
			'name' => 'user_photo',
			'value' => ($model->user_photo != null) 
				? CHtml::image($model->user_photo)
				: null,
			'type' => 'raw',
		],
		'id',
		'department.concatened',		
		'user_fio',		
		'user_position',
		'user_rank',
		'user_telephone',		
		'user_level',
		'sort_index',
		'date_create',
		'date_edit',
		'log_change',
	),
)); ?>
