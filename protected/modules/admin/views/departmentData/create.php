<?php
$this->breadcrumbs=array(
	'Отдел (' . $modelDepartment->concatened . ')' => array('admin', 'idTree'=>$model->id_tree),
	'Создать',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin', 'idTree'=>$model->id_tree), 'icon'=>'user'),
	
	array('label'=>'<hr />','type'=>'raw'),
	array('label'=>'Настройка отдела','url'=>array('options', 'id'=>$modelDepartment->id, 'idTree'=>$modelTree->id), 'icon'=>'cog'),
);

if ($modelDepartment->use_card):
$this->menu = array_merge($this->menu, array(
		array('label'=>'<hr />','type'=>'raw'),
		array('label'=>'Структура отдела','url'=>array('department/updateStructure', 'id'=>$modelDepartment->id), 'icon'=>'list-alt'),
));
endif;
?>

<h1>Создать страницу</h1>
<?php echo $this->renderPartial('../news/_form', 
		array('model'=>$model, 'idTree'=>$model->id_tree, 'modelTree'=>$modelTree)); ?>