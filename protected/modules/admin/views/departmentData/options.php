<?php
$this->breadcrumbs=array(
	'Отдел (' . $model->concatened . ')' => array('admin', 'idTree'=>$model->id_tree),
	'Настройки',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('admin', 'idTree'=>$model->id_tree), 'icon'=>'user'),
	
	array('label'=>'<hr />','type'=>'raw'),
	array('label'=>'Настройка отдела','url'=>array('options', 'id'=>$model->id, 'idTree'=>$model->id_tree), 'icon'=>'cog'),
);

if ($model->use_card):
$this->menu = array_merge($this->menu, array(
		array('label'=>'<hr />','type'=>'raw'),
		array('label'=>'Структура отдела','url'=>array('department/updateStructure', 'id'=>$model->id), 'icon'=>'list-alt'),
));
endif;

?>

<h1>Настройки отдела <?= $model->concatened; ?></h1>


<?php 
	$this->widget('bootstrap.widgets.TbAlert', array('block'=>true)); 	
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'department-form',
)); ?>

	<div class="well">
		<?php echo $form->checkBoxRow($model,'use_card'); ?>
	</div>
	
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>