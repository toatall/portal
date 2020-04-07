<?php
$this->breadcrumbs=array(
	'Новости'=>array('admin', 'idTree'=>$idTree),
	'Управление',
);

$this->menu=array(	
	array('label'=>'Создать новость','url'=>array('create', 'idTree'=>$idTree), 'icon'=>'asterisk'),
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

<h1>Управление новостями</h1>
<h3>Раздел: <?php echo Tree::model()->findByPk($idTree)->name; ?></h3>


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
	'dataProvider' => $model->search($idTree),
	'filter' => $model,
    'rowCssClassExpression' => function ($data) {
	    return $data['date_delete'] == null ? '' : 'danger';
    },
	'columns' => array(
		'id',
        'title',
        'date_create',
        'date_start_pub',
        'date_end_pub',
        array(
            'name'=>'flag_enable',
            'value'=>'$data->flag_enable?"Да":"Нет"',
            'filter'=>['0'=>'Нет', '1'=>'Да'],
        ),
        'author',        
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
            'buttons'=>array(
                'view'=>array(
                    'url'=>'Yii::app()->createUrl("admin/news/view", array("id"=>$data->id,"idTree"=>$data->id_tree))',                    
                ),
                'update'=>array(
                    'url'=>'Yii::app()->createUrl("admin/news/update", array("id"=>$data->id,"idTree"=>$data->id_tree))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("admin/news/delete", array("id"=>$data->id,"idTree"=>$data->id_tree))',
                ),                
            ),
            
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
