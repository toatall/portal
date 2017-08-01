<?php
/* @var $this TelephoneController */
/* @var $model Telephone */

$this->breadcrumbs=array(
	'Телефонный справочник'=>array('admin','idTree'=>$idTree),
	$model->id.' ('.$model->telephone_file.')'=>array('view','id'=>$model->id,'idTree'=>$idTree),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create', 'idTree'=>$idTree), 
        'icon'=>'asterisk'),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id, 'idTree'=>$idTree), 
        'icon'=>'eye-open'),
	array('label'=>'Управление', 'url'=>array('admin', 'idTree'=>$idTree), 'icon'=>'user'),
);
?>

<h1>Изменить телефонный справочник #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'idTree'=>$idTree)); ?>