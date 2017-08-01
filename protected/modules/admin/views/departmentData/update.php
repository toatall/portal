<?php
$this->breadcrumbs=array(
	'Отдел (' . $modelDepartment->concatened . ')' => array('admin', 'idTree'=>$model->id_tree),
	'Изменить',
);


$this->menu=array(
		array('label'=>'Создать','url'=>array('create', 'idTree'=>$modelTree->id), 'icon'=>'asterisk'),
		array('label'=>'Просмотр','url'=>array('view','id'=>$model->id, 'idTree'=>$modelTree->id), 'icon'=>'eye-open'),
		array('label'=>'Управление','url'=>array('admin', 'idTree'=>$modelTree->id), 'icon'=>'user'),
		
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

<h1>Изменить страницу #<?= $model->id; ?></h1>

<?php echo $this->renderPartial('../news/_form', 
		array('model'=>$model, 'idTree'=>$model->id_tree, 'modelTree'=>$modelTree)); ?>