<?php
/* @var $this TelephoneController */
/* @var $model Telephone */

$this->breadcrumbs=array(
	'Телефонный справочник'=>array('admin','idTree'=>$idTree),
	'Управление',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create', 'idTree'=>$idTree), 'icon'=>'asterisk'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('news-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Телефонный справочник</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'telephone-grid',
	'dataProvider'=>$model->searchAdmin($idTree),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'id_organization',
		//'telephone_file',
        'dop_text',
		'author',
		//'dop_text',
		'date_create',
		/*
		'sort',
		'actions_log',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
                'view'=>array(
                    'url'=>'Yii::app()->createUrl("admin/telephone/view", array("id"=>$data->id,"idTree"=>$data->id_tree))',                    
                ),
                'update'=>array(
                    'url'=>'Yii::app()->createUrl("admin/telephone/update", array("id"=>$data->id,"idTree"=>$data->id_tree))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("admin/telephone/delete", array("id"=>$data->id,"idTree"=>$data->id_tree))',
                ),                
            ),
		),
	),
)); ?>
