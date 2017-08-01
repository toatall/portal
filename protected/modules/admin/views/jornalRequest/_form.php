<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'jornal-request-form',
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

	<?php //echo $form->textFieldRow($model,'code_no',array('class'=>'span5','maxlength'=>4)); ?>
    <?php echo $form->dropDownListRow($model,'code_no',
        CHtml::listData(Organization::model()->findAll(),'code','name'),array('class'=>'span6')); ?>

	<?php echo $form->textFieldRow($model,'ifns_ufns_date',array('class'=>'span2')); ?>
    <script type="text/javascript">
        jQuery('#<?php echo CHtml::activeId($model, 'ifns_ufns_date'); ?>').datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0            
        });
    </script>
	<?php echo $form->textFieldRow($model,'ifns_ufns_number',array('class'=>'span2','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'ufns_fns_date',array('class'=>'span2')); ?>
    <script type="text/javascript">
        jQuery('#<?php echo CHtml::activeId($model, 'ufns_fns_date'); ?>').datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0            
        });
    </script>
	<?php echo $form->textFieldRow($model,'ufns_fns_number',array('class'=>'span2','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'fns_ufns_date',array('class'=>'span2')); ?>
    <script type="text/javascript">
        jQuery('#<?php echo CHtml::activeId($model, 'fns_ufns_date'); ?>').datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0            
        });
    </script>
	<?php echo $form->textFieldRow($model,'fns_ufns_number',array('class'=>'span2','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'ufns_ifns_date',array('class'=>'span2')); ?>
    <script type="text/javascript">
        jQuery('#<?php echo CHtml::activeId($model, 'ufns_ifns_date'); ?>').datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0            
        });
    </script>
	<?php echo $form->textFieldRow($model,'ufns_ifns_number',array('class'=>'span2','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'date_execution',array('class'=>'span2')); ?>
    <script type="text/javascript">
        jQuery('#<?php echo CHtml::activeId($model, 'date_execution'); ?>').datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0            
        });
    </script>
	<?php echo $form->textAreaRow($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php //echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>
    <?php echo $form->dropDownListRow($model,'status',array(0=>'На исполнении',1=>'Завершено')); ?>	

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
