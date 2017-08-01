<?php
$this->breadcrumbs=array(
	'Разделы'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Просмотр #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'module',
        array(
            'name'=>'use_organization',
            'value'=>$model->use_organization?'Да':'Нет',
        ),
		'date_create',
		'date_modification',
	),
)); ?>

<?php if ($model->use_organization): ?>
<div class="well" style="background-color: white;">
<?php $this->widget('CTreeView', array(
    'data'=>$model->getListOrganization(0, $model->id, true),
));
?>
<?php endif; ?>
</div>