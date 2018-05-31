<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_start')); ?>:</b>
	<?php echo CHtml::encode($data->date_start); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_end')); ?>:</b>
	<?php echo CHtml::encode($data->date_end); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('organizations')); ?>:</b>
	<?php echo CHtml::encode($data->organizations); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('multi_answer')); ?>:</b>
	<?php echo CHtml::encode($data->multi_answer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($data->date_create); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('date_edit')); ?>:</b>
	<?php echo CHtml::encode($data->date_edit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('log_change')); ?>:</b>
	<?php echo CHtml::encode($data->log_change); ?>
	<br />

	*/ ?>

</div>