<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'sez-data-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>
	<p>В случае отсутствия какой-либо информации в соответствующей графе заявки ставится слово "Нет"</p>

	<?php echo $form->errorSummary($model); ?>
	
	
	
	<?php echo $form->textFieldRow($model,'egrn_fid',array('class'=>'span5','maxlength'=>250)); ?>
	
	
	<?php if (in_array($typeSez, array(3))): ?>
	
	<?php echo $form->textFieldRow($model,'egrn_fid_stat',array('class'=>'span5','maxlength'=>100)); ?>
	
	<?php endif; ?>
	
	
	<?php echo $form->textFieldRow($model,'egrn_inn_actual',array('class'=>'span5','maxlength'=>12)); ?>

	<?php echo $form->textFieldRow($model,'egrn_date_inn_actual',array('class'=>'span5')); ?>

	
	<?php if (in_array($typeSez, array(1,2))): ?>
	
	<?php echo $form->textFieldRow($model,'egrn_inn_not_actual',array('class'=>'span5','maxlength'=>12)); ?>

	<?php echo $form->textFieldRow($model,'egrn_date_inn_not_actual',array('class'=>'span5')); ?>
	
	<?php endif; ?>
	
	
	<?php if (in_array($typeSez, array(2))): ?>
	
	<?php echo $form->textFieldRow($model,'egrn_inn_value_not_actual',array('class'=>'span5','maxlength'=>12)); ?>
	
	<?php endif; ?>
	
	
	<?php echo $form->textFieldRow($model,'egrn_fio',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'egrn_fio_update',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'egrn_date_birth',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'egrn_place_birth',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'egrn_date_dead',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'egrn_ogrnip',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->textFieldRow($model,'egrn_doc_identity_actual',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'egrn_doc_identity_not_actual',array('class'=>'span5','maxlength'=>200)); ?>

	
	<?php if (in_array($typeSez, array(3))): ?>
	
	<?php echo $form->textFieldRow($model,'egrn_variant_write_ponil',array('class'=>'span5','maxlength'=>100)); ?>
	
	<?php endif; ?>
	
	
	<?php if (in_array($typeSez, array(1,2))): ?>
	
	<?php echo $form->textFieldRow($model,'egrn_address_actual_location',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'egrn_code_no_address_actual_location',array('class'=>'span5','maxlength'=>5)); ?>

	<?php echo $form->textFieldRow($model,'egrn_date_reg_location',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'egrn_prev_address_location',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'egrn_code_no_prev_address_location',array('class'=>'span5','maxlength'=>5)); ?>

	<?php echo $form->textFieldRow($model,'egrn_date_departures_address_location',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'object_type_reg',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'object_name_reg',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'objec_fid_reg',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textFieldRow($model,'object_code_no_reg',array('class'=>'span5','maxlength'=>5)); ?>

	<?php echo $form->textFieldRow($model,'object_address_reg',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'object_source_info_reg',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'object_date_register',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'object_date_unregister',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'object_casuse_unregister',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'krsb_code_no',array('class'=>'span5','maxlength'=>5)); ?>

	<?php echo $form->textFieldRow($model,'krsb_kbk',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'krsb_oktmo',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->checkBoxRow($model,'krsb_flag_open_close'); ?>

	<?php echo $form->textFieldRow($model,'krsb_saldo_calc',array('class'=>'span5','maxlength'=>1)); ?>
	
	<?php endif; ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
