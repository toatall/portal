<?php
/* @var $this MentorController */
/* @var $model MentorPost */

$this->breadcrumbs=array(
	'Наставничество'=>array('index'),	
    $model->ways->name=>array('way', 'id'=>$model->id_mentor_ways),
	'Изменить (' . $model->title . ')',
);
?>

<h1>Изменить пост <?php echo $model->title; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>