<?php
$this->breadcrumbs=array(
	'Справочник НО'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('ifns-grid', {
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
	'id'=>'ifns-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'code',
		'name',		
		'date_create',
		'date_modification',
		array(
            'name'=>'enabled',            
            'value'=>'($data->enabled)?"Да":"Нет"',
            'filter'=>array('0'=>'Нет', '1'=>'Да'),    
        ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
