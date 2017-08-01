<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'vks-form',
	'enableAjaxValidation'=>false,
)); ?>

    <p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'theme',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textAreaRow($model,'responsible',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textAreaRow($model,'members_people',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textAreaRow($model,'members_organization',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
    
    <?php       
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
        Yii::app()->getClientScript()->registerCssFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
    ?>
    
	<?php echo $form->textFieldRow($model,'_tempDateStart',array('class'=>'span2')); ?>

    <script type="text/javascript">
        jQuery('#<?php echo CHtml::activeId($model, '_tempDateStart'); ?>').datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0            
        });         
    </script>
    
    
    <?php echo Chtml::activeLabel($model,'_tempTimeStart'); ?>
    <?php
        $this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => '_tempTimeStart',
            'mask' => '99:99',            
            'placeholder' => '*',
            'htmlOptions'=>array(
                'class'=>'span1',
            ),
        ));
    ?>
    
    <?php echo Chtml::activeLabel($model,'duration'); ?>
    <?php
        $this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => 'duration',
            'mask' => '99:99',            
            'placeholder' => '*',
            'htmlOptions'=>array(
                'class'=>'span1',
            ),
        ));
    ?>
    
	<?php //echo $form->textFieldRow($model,'duration',array('class'=>'span1','maxlength'=>20)); ?>

	<?php //echo $form->textFieldRow($model,'date_create',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'date_delete',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'action_log',array('class'=>'span5','maxlength'=>5000)); ?>

	<?php //echo $form->textFieldRow($model,'type_vks',array('class'=>'span5')); ?>

	<div class="form-actions">
    
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
        
        <?php $this->widget('bootstrap.widgets.TbButton', array(
			'url'=>array('admin','idTree'=>$idTree),
			//'type'=>'primary',
			'label'=>'Отмена',
		)); ?>
        
	</div>

<?php $this->endWidget(); ?>
