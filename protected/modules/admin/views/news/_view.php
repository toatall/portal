<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('message2')); ?>:</b>
	<?php echo CHtml::encode($data->message2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<?php echo CHtml::encode($data->author); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('date_start_pub')); ?>:</b>
	<?php echo CHtml::encode($data->date_start_pub); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_end_pub')); ?>:</b>
	<?php echo CHtml::encode($data->date_end_pub); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($data->date_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_modification')); ?>:</b>
	<?php echo CHtml::encode($data->date_modification); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('flag_delete')); ?>:</b>
	<?php echo CHtml::encode($data->flag_delete); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('flag_enable')); ?>:</b>
	<?php echo CHtml::encode($data->flag_enable); ?>
	<br />

	*/ ?>

</div>