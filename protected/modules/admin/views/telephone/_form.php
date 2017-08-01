<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'news-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    ),
)); ?>
    
    <?php if (!$model->isNewRecord): ?>
    <div class="alert in alert-block fade alert-info">
        <a class="close" data-dismiss="alert">x</a>
        <strong>Информация</strong><br />
        Для изменения данных кроме файла справочника поле &laquo;Файл справочника&raquo;         
        следует оставить пустыми.
    </div>
    <?php endif; ?>
    
    <p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>
    
    <?php echo $form->errorSummary($model); ?>
    
    <?php echo $form->dropDownListRow($model,'id_organization',
        CHtml::listData(Telephone::model()->listOrganizations($idTree),'code','name'),
        array('class'=>'span8','maxlength'=>50)); ?>
            
    <br />Файл справочника
    <?php echo $form->fileField($model, 'tel_file'); ?>    
    
    <?php echo $form->textAreaRow($model,'dop_text',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
    
    <?php echo $form->textFieldRow($model,'sort',array('class'=>'span5')); ?>
    
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>
    
    
<?php $this->endWidget(); ?>
