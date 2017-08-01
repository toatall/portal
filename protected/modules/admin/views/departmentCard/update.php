<?php
$this->breadcrumbs=array(
	'Отделы' => ['/admin/department/admin'],
	'Структура отдела ' . $model->department->concatened => 
		['/admin/department/updateStructure', 'id'=>$model->id_department],	
	'Изменение',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create', 'idDepartment'=>$model->id_department), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Управление','url'=>array('/admin/department/updateStructure', 
		'id'=>$model->id_department), 'icon'=>'user'),
	);
?>

<h1>Изменить карточку сотрудника #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>