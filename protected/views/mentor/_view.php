<?php
/* @var $this MentorController */
/* @var $data MentorPost */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_mentor_ways')); ?>:</b>
	<?php echo CHtml::encode($data->id_mentor_ways); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_organization')); ?>:</b>
	<?php echo CHtml::encode($data->id_organization); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('message1')); ?>:</b>
	<?php echo CHtml::encode($data->message1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($data->date_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_update')); ?>:</b>
	<?php echo CHtml::encode($data->date_update); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('date_delete')); ?>:</b>
	<?php echo CHtml::encode($data->date_delete); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<?php echo CHtml::encode($data->author); ?>
	<br />

	*/ ?>

</div>