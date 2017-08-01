<?php

$this->breadcrumbs=array(
	'Отдел (' . $modelDepartment->concatened . ')' => array('admin', 'idTree'=>$model->id_tree),
	$model->title,
);

$this->menu=array(	
	array('label'=>'Создать','url'=>array('create', 'idTree'=>$model->id_tree), 'icon'=>'asterisk'),
	array('label'=>'Изменить',
        'url'=>array('update','id'=>$model->id, 'idTree'=>$model->id_tree), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id, 'idTree'=>$model->id_tree),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin', 'idTree'=>$model->id_tree), 'icon'=>'user'),
	
	array('label'=>'<hr />','type'=>'raw'),
	array('label'=>'Настройка отдела','url'=>array('options', 'id'=>$modelDepartment->id, 'idTree'=>$model->id_tree), 'icon'=>'cog'),
		
);

if ($modelDepartment->use_card):
$this->menu = array_merge($this->menu, array(
		array('label'=>'<hr />','type'=>'raw'),
		array('label'=>'Структура отдела','url'=>array('department/updateStructure', 'id'=>$modelDepartment->id), 'icon'=>'list-alt'),
));
endif;
?>

<h1>Просмотр страницы #<?php echo $model->id; ?></h1>

<?php 
    
    // скрипты для просмотра изображений //    
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/lib/jquery.mousewheel-3.0.6.pack.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/jquery.fancybox.js?v=2.1.5');            
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-media.js?v=1.0.6');
    Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/extension/fancybox/jquery.fancybox.css?v=2.1.5');
                   
?>


<?php if (!$model->isNewRecord && ($model->date_delete!='') && Yii::app()->user->admin): ?>    
    <div class="alert in alert-block fade alert-error">        
        <strong>Данная запись была удалена 
            <?php echo date('d.m.Y H:i:s', strtotime($model->date_delete)); ?>
        </strong>&nbsp;&nbsp;
        <?php 
            $this->widget('bootstrap.widgets.TbButton', array(
            	'url'=>array('restore','id'=>$model->id,'idTree'=>$idTree),
                'type'=>'primary',		
            	'label'=>'Восстановить',
            )); ?>
        <?php 
            $this->widget('bootstrap.widgets.TbButton', array(                    
            	'url'=>array('delete','id'=>$model->id,'idTree'=>$idTree),
                'type'=>'danger',		
            	'label'=>'Удалить безвозвратно',
                'htmlOptions'=>array(
                    'confirm'=>'Вы уверены, что хотите удалить эту запись?',
                ),
            )); ?>
    </div>                    
<?php endif; ?>
    
<script type="text/javascript">
    $(document).ready(function() {
        if ($('.fancybox').length)
        {
            $('.fancybox').fancybox();
        } 
    });
</script>


<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
    //'htmlOptions'=>array('style'=>'color:red;'),
	'attributes'=>array(        
		'id',
        array(
            'name'=>'id_tree',
            'value'=>Tree::model()->findByPk($model->id_tree)->name,
        ),
        'title',
		'author',
		'date_start_pub',
		'date_end_pub',
        array(
		  'name'=>'date_create',
          'value'=>date('d.m.Y H:i:s', strtotime($model->date_create)),
        ),          	
        array(		
		  'name'=>'flag_enable',
          'value'=>$model->flag_enable ? "Да" : "Нет",
        ),       
        array(
            'name'=>'files',
            'type'=>'raw', 
            'value'=>'<div id="preview">'.$model->listFiles($model->id, $model->id_tree).'</div>',
        ),
        array(
            'name'=>'images',
            'type'=>'raw', 
            'value'=>$model->getListImages($model->id, $model->id_tree),            
        ),
        array(
            'name'=>'log_change',
            'type'=>'raw',
            'value'=>LogChange::getLog($model->log_change),
        ),   
        
	),
)); ?>

