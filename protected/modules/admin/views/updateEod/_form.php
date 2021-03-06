<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'update-eod-form',
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
    
    <?php echo $form->textFieldRow($model,'support',array('class'=>'span5','maxlength'=>250,'prepend'=>'<i class="icon-pencil"></i>')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>250,'prepend'=>'<i class="icon-pencil"></i>')); ?>

	<?php echo $form->textFieldRow($model,'path',array('class'=>'span5','maxlength'=>500,'prepend'=>'<i class="icon-globe"></i>')); ?>

	<?php echo $form->textFieldRow($model,'date_update',array('class'=>'span2','prepend'=>'<i class="icon-calendar"></i>')); ?>
    <script type="text/javascript">
        jQuery('#<?php echo CHtml::activeId($model, 'date_update'); ?>').datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0            
        });         
    </script>
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
        
        <?php $this->widget('bootstrap.widgets.TbButton', array(
			'url'=>array('admin','idTree'=>$idTree),		
			'label'=>'Отмена',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
