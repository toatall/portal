<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'rating-main-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
	),
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->dropDownListRow($model,'rating_year',$model->years,array('class'=>'span3')); ?>

	<?php echo $form->dropDownListRow($model,'rating_period',$model->periods,array('class'=>'span3')); ?>

	<?php echo $form->textAreaRow($model,'note',array('class'=>'span5','maxlength'=>-1,'rows'=>5)); ?>
	
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
	            $files = CHtml::listData($model->files, 'id', 'file_name');        	            
	            if (count($files)):
        ?>
        <hr />
        <strong>Отметьте файлы для удаления:</strong><br />
        <?php
            echo CHtml::checkBoxList('RatingFile[deleteFile][]', '', 
                $files,
                array('labelOptions'=>array('style'=>'display:inline;'), 'style'=>'margin-top:0;')
            );            
        ?>
        <?php
        		endif;
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
