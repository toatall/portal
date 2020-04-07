<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'id'=>'rating-main-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
	),
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->dropDownListControlGroup($model,'rating_year',$model->years,array('class'=>'span3')); ?>

	<?php echo $form->dropDownListControlGroup($model,'rating_period',$model->periods,array('class'=>'span3')); ?>

	<?php echo $form->textAreaControlGroup($model,'note',array('maxlength'=>-1,'ControlGroups'=>5)); ?>
	
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
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
	</div>

<?php $this->endWidget(); ?>
