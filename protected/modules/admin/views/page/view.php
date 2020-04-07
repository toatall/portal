<?php

$this->breadcrumbs=array(
	$modelTree->name=>array('admin', 'idTree'=>$modelTree->id),
	$model->title,
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create', 'idTree'=>$modelTree->id), 'icon'=>'asterisk'),
	array('label'=>'Изменить',
        'url'=>array('update','id'=>$model->id, 'idTree'=>$modelTree->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id, 'idTree'=>$modelTree->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin', 'idTree'=>$modelTree->id), 'icon'=>'user'),
);
?>

<h1<?php  if (Yii::app()->user->admin && $model->date_delete != '') 
    echo ' style="color:red; text-decoration:line-through;"'; ?>>Просмотр новости #<?php echo $model->id; ?></h1>

<?php     
    // скрипты для просмотра изображений //    
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/lib/jquery.mousewheel-3.0.6.pack.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/jquery.fancybox.js?v=2.1.5');            
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5');    
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-media.js?v=1.0.6');
    Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/extension/fancybox/jquery.fancybox.css?v=2.1.5');                   
?>

<script type="text/javascript">
    $(document).ready(function() {
        if ($('.fancybox').length)
        {
            $('.fancybox').fancybox();
        } 
    });
</script>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,   
	'attributes'=>array(        
		'id',
        array(
            'name'=>'id_tree',
            'value'=>$modelTree->name,
        ),        
        'title',
		'author',
		'date_start_pub',
		'date_end_pub',
		'date_create',		
        array(		
		  'name'=>'flag_enable',
          'value'=>$model->flag_enable ? "Да" : "Нет",
        ),
        array(
            'name'=>'files',
            'type'=>'raw', 
            'value'=>'<div id="preview">'.$model->listFiles($model->id, $modelTree->id).'</div>',   
        ),
        array(
            'name'=>'images',
            'type'=>'raw', 
            'value'=>$model->getListImages($model->id, $modelTree->id),   
        ),        
        array(
            'name'=>'log_change',
            'type'=>'raw',
            'value'=>$model->logChangeText,
        ), 
	),
)); ?>

