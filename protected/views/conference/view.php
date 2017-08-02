<?php
	
	$this->pageTitle = $model->typeName . ': ' . $model->theme;
	
	$this->breadcrumbs=array(
		$model->typeName => array('conference/' . $model->typeController),
		$model->theme . ' (#' . $model->id . ')',
	);
?>

<h1><?= $model->theme ?></h1>
<br />

<div class="well">

	<b><?php echo CHtml::encode($model->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($model->id); ?>
	<br />

	<b><?php echo CHtml::encode($model->getAttributeLabel('_tempDateStart')); ?>:</b>
	<?php echo CHtml::encode($model->_tempDateStart); ?>
	<br />

	<b><?php echo CHtml::encode($model->getAttributeLabel('_tempTimeStart')); ?>:</b>
	<?php echo CHtml::encode($model->_tempTimeStart . ($model->time_start_msk ? ' (МСК)' : '')); ?>
	<br />
	
	<b><?php echo CHtml::encode($model->getAttributeLabel('theme')); ?>:</b>
	<?php echo CHtml::encode($model->theme); ?>
	<br />
	
	<b><?php echo CHtml::encode($model->getAttributeLabel('members_people')); ?>:</b>
	<?php echo CHtml::encode($model->members_people); ?>
	<br />
	
	<b><?php echo CHtml::encode($model->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($model->note); ?>
	<br />
		
	<b><?php echo CHtml::encode($model->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($model->date_create); ?>
	<br />
		
</div>
<?= CHtml::link('Назад', array('conference/' . $model->typeController), array('class' => 'btn btn-primary')) ?>


