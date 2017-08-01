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

</div>