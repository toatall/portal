<?php
$this->breadcrumbs=array(
	'Голосование'=>array('admin'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('vote-main-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление</h1>


<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'vote-main-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'date_start',
		'date_end',
		'organizations',
		'multi_answer',
		/*
		'date_create',
		'date_edit',
		'log_change',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
