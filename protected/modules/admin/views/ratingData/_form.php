<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'id'=>'rating-main-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    ),
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->checkBoxControlGroup($model,'order_asc'); ?>

	<?php echo $form->textAreaControlGroup($model,'note',array('maxlength'=>-1,'ControlGroups'=>10)); ?>
	
	<div class="well">
        <h4>Загрузка файлов</h4>
        <?php
            $this->widget('CMultiFileUpload', array(
                'name'=>'files',
                'accept'=>'*',
                'duplicate'=>'Файл уже выбран!',
                'remove'=>'<i class="glyphicon glyphicon-remove"></i>',
            ));        
        ?>
        <?php
        
        	if (!$model->isNewRecord):
	           if (count($model->files))
	               echo FileHelper::showFilesUpload(CHtml::listData($model->files,'id','file_name'));
            endif;
        ?>
    </div>
	
	<div class="form-actions">
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
	</div>

<?php $this->endWidget(); ?>
