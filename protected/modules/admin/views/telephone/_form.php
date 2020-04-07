<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
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
    
    <?php echo $form->dropDownListControlGroup($model,'id_organization',
        CHtml::listData(Telephone::model()->listOrganizations($idTree),'code','name'),
        array('class'=>'span8','maxlength'=>50)); ?>
            
    <br />Файл справочника
    <?php echo $form->fileField($model, 'tel_file'); ?>    
    
    <?php echo $form->textAreaControlGroup($model,'dop_text',array('ControlGroups'=>6, 'cols'=>50)); ?>
    
    <?php echo $form->textFieldControlGroup($model,'sort',array('class'=>'span5')); ?>
    
    
	<div class="form-actions">
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
	</div>
    
    
<?php $this->endWidget(); ?>
