<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_tree')); ?>:</b>
	<?php echo CHtml::encode($data->id_tree); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_organization')); ?>:</b>
	<?php echo CHtml::encode($data->id_organization); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('department_index')); ?>:</b>
	<?php echo CHtml::encode($data->department_index); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('department_name')); ?>:</b>
	<?php echo CHtml::encode($data->department_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($data->date_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_edit')); ?>:</b>
	<?php echo CHtml::encode($data->date_edit); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<?php echo CHtml::encode($data->author); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('log_change')); ?>:</b>
	<?php echo CHtml::encode($data->log_change); ?>
	<br />

	*/ ?>

</div>