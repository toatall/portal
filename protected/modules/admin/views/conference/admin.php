<?php

$title = $model->typeName;	

$this->breadcrumbs=array(
	$title=> array('admin'),
	'Управление',
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
);
?>

<h1><?= $title ?></h1>

<?php 
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
    )); 
?>
