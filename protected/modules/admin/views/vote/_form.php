<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'id'=>'vote-main-form',
	'enableAjaxValidation'=>false,
)); ?>


<?php    
//    Yii::app()->clientScript->registerScriptFile(
//        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
//    Yii::app()->clientScript->registerScriptFile(
//        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
//    Yii::app()->getClientScript()->registerCssFile(
//        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');

    $assetDatepicker = new DatepickerAsset();
    $assetDatepicker->register();
?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldControlGroup($model,'date_start',array('class'=>'datepicker')); ?>

	<?php echo $form->textFieldControlGroup($model,'date_end',array('class'=>'datepicker')); ?>
	
	<div class="alert alert-info">	
		<?php echo $form->checkBoxListControlGroup($model,'orgList', CHtml::listData(Organization::model()->findAll(), 'code', 'name')) ?>
	</div>

	<?php echo $form->checkBoxControlGroup($model,'multi_answer'); ?>
	
	<?php echo $form->checkBoxControlGroup($model,'on_general_page'); ?>
	
	<br />
	<?php echo $form->textAreaControlGroup($model,'description',array('rows'=>6)); ?>
	
	<div class="form-actions">
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
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
