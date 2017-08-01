<?php
$this->breadcrumbs=array(
	'Отделы' => ['/admin/department/admin'],
	'Структура отдела ' . $model->department->concatened => 
		['/admin/department/updateStructure', 'id'=>$model->id_department],
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('department/updateStructure', 
			'id'=>$model->id_department), 'icon'=>'user'),
);
?>

<h1>Создать карточку сотрудника отдела</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>