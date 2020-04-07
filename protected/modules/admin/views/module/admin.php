<?php
$this->breadcrumbs=array(
	'Модули'=>array('index'),
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
	$.fn.yiiGridView.update('module-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление</h1>


<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn btn-success')); ?><br /><br />
<div class="search-form panel panel-default" style="display:none">
    <div class="panel-body">
        <?php $this->renderPartial('_search',array(
            'model'=>$model,
        )); ?>
    </div>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.BsGridView',array(
	'id'=>'module-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'description',
        array(
            'name'=>'only_one',
            'filter'=>array(0=>'Нет',1=>'Да'),
            'value'=>'($data->only_one ? "Да" : "Нет")',
        ),
		'date_create',
        'author',		
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
