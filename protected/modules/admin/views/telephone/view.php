<?php

$this->breadcrumbs=array(
	'Телефонный справочник'=>array('admin', 'idTree'=>$idTree),
	$model->id.' ('.$model->telephone_file.')',
);


$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create', 'idTree'=>$idTree), 'icon'=>'asterisk'),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->id, 'idTree'=>$idTree), 'icon'=>'pencil'),
	array('label'=>'Удалить', 'url'=>'#', 
        'linkOptions'=>array('submit'=>array('delete','id'=>$model->id, 'idTree'=>$idTree),
        'confirm'=>'Вы уверены, что хотите удалить эту запись?',
    ),'icon'=>'trash'),
	array('label'=>'Управление', 'url'=>array('admin', 'idTree'=>$idTree), 'icon'=>'user'),
);
?>

<h1>Телефонный справочник #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',		
        array(
            'name'=>'id_organization',
            'value'=>$model->org->code.' - '.$model->org->name,
        ),
        array(
            'name'=>'telephone_file',
            'value'=>CHtml::link($model->telephone_file, 
                Yii::app()->baseUrl.'/files/telephones/'.$model->telephone_file, array('target'=>'_blank')),
            'type'=>'raw',
        ),		
		'author',
		'dop_text',
		'date_create',
		'sort',       
	),
)); ?>
