<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'vote-main-form',
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

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'date_start',array('class'=>'span2')); ?>

	<?php echo $form->textFieldRow($model,'date_end',array('class'=>'span2')); ?>
	
	<div class="alert alert-info">	
		<?php echo $form->checkBoxList($model,'orgList', CHtml::listData(Organization::model()->findAll(), 'code', 'name')) ?>
	</div>

	<?php echo $form->checkBoxRow($model,'multi_answer'); ?>
	
	<?php echo $form->checkBoxRow($model,'on_general_page'); ?>
	
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">

    jQuery('#<?php echo CHtml::activeId($model, 'date_start'); ?>').datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    }); 
    
    jQuery('#<?php echo CHtml::activeId($model, 'date_end'); ?>').datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });   
    
</script>
