<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'id'=>'vks-form',
	'enableAjaxValidation'=>false,
)); ?>

    <p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldControlGroup($model,'theme',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textAreaControlGroup($model,'responsible',array('ControlGroups'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textAreaControlGroup($model,'members_people',array('ControlGroups'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textAreaControlGroup($model,'members_organization',array('ControlGroups'=>6, 'cols'=>50, 'class'=>'span8')); ?>
    
    <?php
        /*
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
        Yii::app()->getClientScript()->registerCssFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
        */
        $assetDatepicker = new DatepickerAsset();
        $assetDatepicker->register();
    ?>
    
	<?php echo $form->textFieldControlGroup($model,'_tempDateStart',array('class'=>'datepicker')); ?>

    
    <?php echo Chtml::activeLabel($model,'_tempTimeStart'); ?>
    <?php
        $this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => '_tempTimeStart',
            'mask' => '99:99',            
            'placeholder' => '*',
        ));
    ?>
    
    <?php echo Chtml::activeLabel($model,'duration'); ?>
    <?php
        $this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => 'duration',
            'mask' => '99:99',            
            'placeholder' => '*',
        ));
    ?>

	<div class="form-actions">

        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
		<?= BsHtml::link('Отмена', ['admin', 'idTree' => $idTree], ['class' => 'btn btn-default']) ?>
        
        
	</div>

<?php $this->endWidget(); ?>
