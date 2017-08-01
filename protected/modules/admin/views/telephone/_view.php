<?php
/* @var $this TelephoneController */
/* @var $data Telephone */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ifns_code')); ?>:</b>
	<?php echo CHtml::encode($data->ifns_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telephone_file')); ?>:</b>
	<?php echo CHtml::encode($data->telephone_file); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<?php echo CHtml::encode($data->author); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dop_text')); ?>:</b>
	<?php echo CHtml::encode($data->dop_text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($data->date_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sort')); ?>:</b>
	<?php echo CHtml::encode($data->sort); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('actions_log')); ?>:</b>
	<?php echo CHtml::encode($data->actions_log); ?>
	<br />

	*/ ?>

</div>