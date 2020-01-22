<?php
/* @var $this MentorController */
/* @var $model MentorPost */

$this->breadcrumbs=array(
    'Наставничество'=>array('index'),	
    $model->ways->name=>array('way', 'id'=>$model->id_mentor_ways),
	$model->title,
);

$this->menu=array(
	array('label'=>'List MentorPost', 'url'=>array('index')),
	array('label'=>'Create MentorPost', 'url'=>array('create')),
	array('label'=>'Update MentorPost', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MentorPost', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MentorPost', 'url'=>array('admin')),
);
?>

<h1>Просмотр #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_mentor_ways',
		'id_organization',
		'title',
		'message1',
		'date_create',
		'date_update',
		'date_delete',
		'author',
	),
)); ?>
