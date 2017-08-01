<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_department')); ?>:</b>
	<?php echo CHtml::encode($data->id_department); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_user')); ?>:</b>
	<?php echo CHtml::encode($data->id_user); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_fio')); ?>:</b>
	<?php echo CHtml::encode($data->user_fio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_rank')); ?>:</b>
	<?php echo CHtml::encode($data->user_rank); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_position')); ?>:</b>
	<?php echo CHtml::encode($data->user_position); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_telephone')); ?>:</b>
	<?php echo CHtml::encode($data->user_telephone); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('user_photo')); ?>:</b>
	<?php echo CHtml::encode($data->user_photo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_level')); ?>:</b>
	<?php echo CHtml::encode($data->user_level); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sort_index')); ?>:</b>
	<?php echo CHtml::encode($data->sort_index); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_create')); ?>:</b>
	<?php echo CHtml::encode($data->date_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_edit')); ?>:</b>
	<?php echo CHtml::encode($data->date_edit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('log_change')); ?>:</b>
	<?php echo CHtml::encode($data->log_change); ?>
	<br />

	*/ ?>

</div>