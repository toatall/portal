<?php
$this->breadcrumbs=array(
	'Новости'=>array('admin', 'idTree'=>$idTree),
	$model->title=>array('view','id'=>$model->id),
	'Восстановление',
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create', 'idTree'=>$idTree), 'icon'=>'asterisk'),
	array('label'=>'Удалить','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id, 'idTree'=>$idTree),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление','url'=>array('admin', 'idTree'=>$idTree), 'icon'=>'user'),
);
?>

<h1>Воосстановить новость #<?php echo $model->id; ?></h1>

<hr />

<?php $this->widget('bootstrap.widgets.TbButton', array(
	'url'=>array('restore','id'=>$model->id,'idTree'=>$idTree),
    'type'=>'primary',		
	'label'=>'Восстановить',
)); ?>

<?php $this->widget('bootstrap.widgets.TbButton', array(
	'url'=>array('restore','id'=>$model->id,'idTree'=>$idTree),
    'type'=>'primary',		
	'label'=>'Удалить',
)); ?>

<?php $this->widget('bootstrap.widgets.TbButton', array(
	'url'=>array('admin','idTree'=>$idTree),	
	'label'=>'Отмена',
)); ?>