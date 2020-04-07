<?php
$this->breadcrumbs=array(
	'Организации'=>array('index'),
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
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('organization-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Организации</h1>


<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn btn-success')); ?><br /><br />
<div class="search-form panel panel-default" style="display:none">
    <div class="panel-body">
        <?php $this->renderPartial('_search',array(
            'model'=>$model,
        )); ?>
    </div>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.BsGridView',array(
	'id'=>'organization-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'code',
		'name',
		'sort',
		'date_create',
		'date_edit',
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
            'buttons' => [
                'view' => [
                    'url' => function ($data) {
			            return $this->createUrl('/admin/organization/view', ['code'=>$data->code]);
                    }, //['/admin/organization/view', 'code' => ]
                ],
            ],
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
