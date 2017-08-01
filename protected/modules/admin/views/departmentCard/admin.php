<?php
$this->breadcrumbs=array(
	'Department Cards'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список DepartmentCard','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать DepartmentCard','url'=>array('create'), 'icon'=>'asterisk'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('department-card-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление Department Cards</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'department-card-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'id_department',
		'id_user',
		'user_fio',
		'user_rank',
		'user_position',
		/*
		'user_telephone',
		'user_photo',
		'user_level',
		'sort_index',
		'date_create',
		'date_edit',
		'log_change',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
