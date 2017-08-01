<?php
$this->breadcrumbs=array(
	'Меню'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create','typeMenu'=>$model->type_menu), 'icon'=>'asterisk'),
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

<h1>Просмотр меню ИД#<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
        array(
            'name'=>'id_parent',
            'value'=>($model->id_parent==0) ? 'Родитель' 
                : Menu::model()->findByPk($model->id_parent)->name,  
        ),
        array(
            'name'=>'type_menu',
            'value'=>$model->type_menu==1 ? 'Верхнее меню' : ($model->type_menu==2 ? 'Левое меню' : null),
        ),
		'name',
		'link',
		'submenu_code',
        array(
            'name'=>'date_create',
            'value'=>date('d.m.Y H:i:s', strtotime($model->date_create)),
        ),
        array(
            'name'=>'date_edit',
            'value'=>date('d.m.Y H:i:s', strtotime($model->date_edit)),
        ),
		'author',
	),
)); ?>
