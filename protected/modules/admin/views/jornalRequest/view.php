<?php
$this->breadcrumbs=array(
	'Журнал заявок'=>array('admin','idTree'=>$idTree),
	'#'.$model->id,
);

$this->menu=array(
	array('label'=>'Созадать','url'=>array('create','idTree'=>$idTree), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id,'idTree'=>$idTree), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#','linkOptions'=>array(
        'submit'=>array('delete','id'=>$model->id,'idTree'=>$idTree),
        'confirm'=>'Вы уверены, что хотите удалить эту запись?'
    ),'icon'=>'trash'),
	array('label'=>'Управление','url'=>array('admin','idTree'=>$idTree), 'icon'=>'user'),
);
?>

<h1>Просмотр заявки #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'code_no',
        array(
            'name'=>'Наименование НО',
            'value'=>Organization::model()->findByPk($model->code_no)->name,
        ),
		'ifns_ufns_date',
		'ifns_ufns_number',
		'ufns_fns_date',
		'ufns_fns_number',
		'fns_ufns_date',
		'fns_ufns_number',
		'ufns_ifns_date',
		'ufns_ifns_number',
		'date_execution',
		'description',
        array(
            'name'=>'status',
            'value'=>($model->status ? 'Завершено' : 'На исполнении'),
        ),
        array(
		  'name'=>'date_create',
          'value'=>date('d.m.Y H:i:s', strtotime($model->date_create)),
        ),
        array(
		  'name'=>'log_change',
          'type'=>'raw',
          'value'=>LogChange::getLog($model->log_change),
        ),
	),
)); ?>
