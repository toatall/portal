<?php
$this->breadcrumbs=array(
	'ВКС ФНС'=>array('admin','idTree'=>$idTree),
	'Управление',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create','idTree'=>$idTree), 'icon'=>'asterisk'),
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

<h1>ВКС ФНС</h1>

<style type="text/css">
    .delete-row {
        color: #D50000;
        text-decoration: line-through;
    }
</style>


<?php /*echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
    'idTree'=>$idTree,
)); ?>
</div><!-- search-form -->

<?php*/ $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'vksFns-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'rowCssClassExpression' => '($data["date_delete"] == "") ? "" : "delete-row"',
	'columns'=>array(
		'id',
		'theme',
		'responsible',
		'members_people',
		'members_organization',
		'date_start',		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
                'view'=>array(
                    'url'=>'Yii::app()->createUrl("admin/vksFns/view", array("id"=>$data->id,"idTree"=>'.$idTree.'))',                    
                ),
                'update'=>array(
                    'url'=>'Yii::app()->createUrl("admin/vksFns/update", array("id"=>$data->id,"idTree"=>'.$idTree.'))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("admin/vksFns/delete", array("id"=>$data->id,"idTree"=>'.$idTree.'))',
                ),                
            ),
		),
	),
)); ?>
