<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'id'=>'conference-form',
	'enableAjaxValidation'=>false,
)); ?>

    <p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldControlGroup($model,'theme',array('class'=>'span5','maxlength'=>500)); ?>
	
	<?php if ($model->type_conference == Conference::TYPE_VKS_UFNS): ?>
	<?php echo $form->textAreaControlGroup($model,'responsible',array('ControlGroups'=>6, 'cols'=>50)); ?>
	<?php endif; ?>

	<?php echo $form->textAreaControlGroup($model,'members_people',array('ControlGroups'=>6, 'cols'=>50)); ?>

	<?php if ($model->type_conference == Conference::TYPE_VKS_UFNS): ?>
	<?php echo $form->textAreaControlGroup($model,'members_organization',array('ControlGroups'=>6, 'cols'=>50)); ?>
	<?php endif; ?>
    
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
            'placeholder' => '-',
            'htmlOptions' => [
                'class' => 'form-control',
            ],
        ));
    ?>
    
    <?php if ($model->type_conference !== Conference::TYPE_VKS_FNS): ?>
    <?php echo Chtml::activeLabel($model,'duration'); ?>
    <?php
        $this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => 'duration',
            'mask' => '99:99',            
            'placeholder' => '-',
            'htmlOptions' => [
                'class' => 'form-control',
            ],
        ));
    ?>
    <?php endif; ?>
    
    <?php if ($model->type_conference == Conference::TYPE_VKS_FNS): ?>
    <?php echo $form->checkBoxControlGroup($model,'time_start_msk'); ?>
    <?php endif; ?>
    
    <?php if ($model->type_conference == Conference::TYPE_CONFERENCE): ?>
    <?= $form->textFieldControlGroup($model,'place',array('class'=>'span5')) ?>
    <br />
    <?= $form->checkBoxControlGroup($model,'is_confidential'); ?>    
    <?php endif; ?>
	
	<?= $form->textAreaControlGroup($model,'note',array('ControlGroups'=>6, 'cols'=>50)); ?>
	
	<div class="form-actions">
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
		<?= BsHtml::link('Отмена', ['admin'], ['class' => 'btn btn-default']) ?>
	</div>

<?php $this->endWidget(); ?>
