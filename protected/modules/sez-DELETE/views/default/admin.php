<?php
$this->breadcrumbs=array(
	'Система электронных заявок'=>array('admin'),
	'Управление',
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sez-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<style type="text/css">
	thead th { background: none; }
</style>

<h1>Управление</h1>

<?php if (Yii::app()->user->inRole(['admin', 'sezIFNS', 'sezAdmin'])): ?>
	<?= CHtml::link('Создать', ['create'], ['class'=>'btn btn-primary']) ?>
<?php endif; ?><br /><br />


<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'sez-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'code_no',
		'type_sez',
		'status',
		'message',
		'id_author',
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
