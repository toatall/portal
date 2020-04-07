<?php
$this->breadcrumbs=array(
	$modelTree->name=>array('admin', 'idTree'=>$modelTree->id),
	'Управление',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create', 'idTree'=>$modelTree->id), 'icon'=>'asterisk'),
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

<h1>Управление страницами</h1>
<h3>Раздел: <?php echo $modelTree->name; ?></h3>


<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn btn-success')); ?>
<div class="search-form panel panel-default" style="display:none">
    <div class="panel-body">
        <?php $this->renderPartial('_search',array(
            'model'=>$model,
        )); ?>
    </div>
</div><!-- search-form -->


<style type="text/css">

.grid-view .filters select:focus
{
    width: auto;
    position: relative;
}

</style>

<style type="text/css">
    .delete-row {
        color: #D50000;
        text-decoration: line-through;
    }
</style>

<?php $this->widget('bootstrap.widgets.BsGridView',array(
	'id'=>'news-grid',
	'dataProvider'=>$model->search($modelTree->id),
	'filter'=>$model,
    'rowCssClassExpression' => '$data["date_delete"] == "" ? "" : "delete-row"',
	'columns'=>array(
		'id',
        array(
            'name'=>'title',
            'value'=>$model->title,            
        ),		
        'date_create',        
        'date_start_pub',
        'date_end_pub',
        array(
            'name'=>'flag_enable',
            'value'=>'$data->flag_enable?"Да":"Нет"',
            'filter'=>array('0'=>'Нет', '1'=>'Да'),
        ),        
        'author',           
		array(
			'class'=>'bootstrap.widgets.BSButtonColumn',
            'buttons'=>array(
                'view'=>array(
                    'url'=>'Yii::app()->createUrl("admin/page/view", array("id"=>$data->id,"idTree"=>$data->id_tree))',                    
                ),
                'update'=>array(
                    'url'=>'Yii::app()->createUrl("admin/page/update", array("id"=>$data->id,"idTree"=>$data->id_tree))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("admin/page/delete", array("id"=>$data->id,"idTree"=>$data->id_tree))',
                ),                
            ),
            
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
