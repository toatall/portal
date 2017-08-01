<?php

$title = $model->typeName;	

$this->breadcrumbs=array(
	$title=> array('admin'),
	'Управление',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-button').click(function(){
		$('.search-form').toggle();
		return false;
	});
	$('.search-form form').submit(function(){
		$.fn.yiiGridView.update('vks-grid', {
			data: $(this).serialize()
		});
		return false;
	});
");
?>

<h1><?= $title ?></h1>

<?php /*echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
    'idTree'=>$idTree,
)); ?>
</div><!-- search-form -->

<?php*/ 

$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'vksFns-grid',
	'dataProvider'=>$model->search($model->type_conference),
	'filter'=>$model,
    'rowCssClassExpression' => '($data["date_delete"] == "") ? "" : "delete-row"',
	'columns'=>array(
		'id',
		'theme',
		//'responsible',
		'members_people',
		//'members_organization',
		'date_start',
		'date_create',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',            
		),
	),
)); ?>
