<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('theme')); ?>:</b>
	<?php echo CHtml::encode($data->theme); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('responsible')); ?>:</b>
	<?php echo CHtml::encode($data->responsible); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('members_people')); ?>:</b>
	<?php echo CHtml::encode($data->members_people); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('members_organization')); ?>:</b>
	<?php echo CHtml::encode($data->members_organization); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_start')); ?>:</b>
	<?php echo CHtml::encode($data->date_start); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duration')); ?>:</b>
	<?php echo CHtml::encode($data->duration); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($data->date_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_delete')); ?>:</b>
	<?php echo CHtml::encode($data->date_delete); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('action_log')); ?>:</b>
	<?php echo CHtml::encode($data->action_log); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_vks')); ?>:</b>
	<?php echo CHtml::encode($data->type_vks); ?>
	<br />

	*/ ?>

</div>