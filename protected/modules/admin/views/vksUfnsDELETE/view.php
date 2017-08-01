<?php
$this->breadcrumbs=array(
	'ВКС УФНС'=>array('admin','idTree'=>$idTree),
	$model->id,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create','idTree'=>$idTree), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id,'idTree'=>$idTree), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#', 'icon'=>'trash',
        'linkOptions'=>array('submit'=>array('delete','id'=>$model->id,'idTree'=>$idTree),
        'confirm'=>'Вы уверены что хотите удалить "'.$model->theme.'"?')),
	array('label'=>'Управление','url'=>array('admin','idTree'=>$idTree), 'icon'=>'user'),
);
?>

<h1>ВКС УФНС #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'theme',
		'responsible',
		'members_people',
		'members_organization',
        array(
            'name'=>'date_start',
            'value'=>$model->_tempDateStart.' '.$model->_tempTimeStart,
        ),
		
		'duration',
		'date_create',
		array(
            'name'=>'log_change',
            'value'=>LogChange::getLog($model->log_change),
            'type'=>'raw',
        ),
	),
)); ?>
