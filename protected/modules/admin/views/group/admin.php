<?php
$this->breadcrumbs=array(
	'Группы'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('admin'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});n
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('group-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление группами</h1>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn btn-success')); ?><br /><br />
<div class="search-form panel panel-default" style="display:none">
    <div class="panel-body">
        <?php $this->renderPartial('_search',array(
            'model'=>$model,
        )); ?>
    </div>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.BsGridView',array(
	'id'=>'group-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'description',
        array(
            'name'=>'date_create',
            'value'=>'$data->date_create',
            'filter'=>false,
        ),
        array(
            'name'=>'date_edit',
            'value'=>'$data->date_edit',
            'filter'=>false,
        ),		
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
