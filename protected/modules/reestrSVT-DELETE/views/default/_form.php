<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'reestr-svt-form',
	'enableAjaxValidation'=>false,
)); ?>
	
	
	
	<?php 	
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
		Yii::app()->getClientScript()->registerCssFile(
			Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');	
	?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
	
	
	
	<?php echo $form->dropDownListRow($model,'code_no',
        CHtml::listData(User::userOrganizations(),'code','fullName'),
        array('class'=>'span8','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'date_fault',array('class'=>'span5', 'prepend'=>'<i class="icon-calendar"></i>')); ?>
	<?php 
		Yii::app()->clientScript->registerScript('script_date_fault', "
			jQuery('#" . CHtml::activeId($model, 'date_fault') . "').datepicker({
				'format':'dd.mm.yyyy',
				'autoclose':'true',
				'todayBtn':'linked',
				'language':'ru',
				'weekStart':0
			});");
	?>
		
	<?php echo $form->textFieldRow($model,'device_fault',array('class'=>'span5','maxlength'=>500)); ?>
	
	<?php echo $form->textFieldRow($model,'number_inventary',array('class'=>'span5','maxlength'=>30)); ?>
	
	<?php echo $form->textAreaRow($model,'fault_description',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
	
	<?php echo $form->textFieldRow($model,'date_appeal_fku',array('class'=>'span5', 'prepend'=>'<i class="icon-calendar"></i>')); ?>
	<?php 
		Yii::app()->clientScript->registerScript('script_date_appeal_fku', "
			jQuery('#" . CHtml::activeId($model, 'date_appeal_fku') . "').datepicker({
				'format':'dd.mm.yyyy',
				'autoclose':'true',
				'todayBtn':'linked',
				'language':'ru',
				'weekStart':0
			});");
	?>
	
	<?php echo $form->textFieldRow($model,'number_appeal_fku',array('class'=>'span5','maxlength'=>30)); ?>

	

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
		&nbsp;&nbsp;
		<?= CHtml::link('Отмена', ['admin'], ['class'=>'btn btn-default'])?>
	</div>

<?php $this->endWidget(); ?>
