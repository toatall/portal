<?php
/* @var $this MentorController */
/* @var $model MentorPost */

$this->breadcrumbs=array(
	'Наставничество'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'List MentorPost', 'url'=>array('index')),
	array('label'=>'Manage MentorPost', 'url'=>array('admin')),
);
?>

<h1>Создать пост</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>