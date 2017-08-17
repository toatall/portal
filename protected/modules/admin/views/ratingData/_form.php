<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'rating-main-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    ),
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->checkBoxRow($model,'order_asc'); ?>

	<?php echo $form->textAreaRow($model,'note',array('class'=>'span5','maxlength'=>-1,'rows'=>10)); ?>
	
	<div class="well">
        <h4>Загрузка файлов</h4>
        <?php
            $this->widget('CMultiFileUpload', array(
                'name'=>'files',
                'accept'=>'*',
                'duplicate'=>'Файл уже выбран!',
                'remove'=>'<i class="icon-remove"></i>',
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
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
