<?php
$this->breadcrumbs=array(
	'Структура сайта'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#','linkOptions'=>array(
        'submit'=>array('delete','id'=>$model->id),
        'confirm'=>'Вы уверены что хотите удалить "'.$model->name.'"? Все дочерние подразделы будут удалены!'),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Просмотр #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.BsDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
            'label'=>$model->getAttributeLabel('id_parent'),
            'value'=>$model->getNameById($model->id_parent),
        ),
		'name',
		'sort',
        'author',
		'module',        
		array(
            'label'=>$model->getAttributeLabel('use_tape'),
            'value'=>$model->use_tape?'Да':'Нет',
        ),
		'date_create',		
        array(
            'label'=>'Доступ',
            'type'=>'raw',
            'value'=>Access::getListGroupUser($model->id),
        ),
        array(
            'name'=>'log_change',
            'type'=>'raw',
            'value'=>$model->logChangeText,
        ),       
	),
)); ?>
