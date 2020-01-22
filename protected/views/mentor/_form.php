<?php
/* @var $this MentorController */
/* @var $model MentorPost */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mentor-post-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    ),
)); ?>
	
	
	<?php
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/ckeditor/ckeditor/ckeditor.js');
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
        Yii::app()->getClientScript()->registerCssFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');

        // for tags    
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->baseUrl.'/extension/bootstrap-tokenfield/bootstrap-tokenfield.min.css');
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->baseUrl.'/extension/bootstrap-tokenfield/tokenfield-typeahead.min.css');   
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->baseUrl.'/extension/bootstrap-tokenfield/form-control.css'); 
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/bootstrap-tokenfield/bootstrap-tokenfield.js');   

        // jQuery ui
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/jquery-ui/jquery-ui.min.js');   
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->baseUrl.'/extension/jquery-ui/jquery-ui.min.css');  
    ?>
    
    
    
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_mentor_ways'); ?>
		<?php echo $form->dropDownList($model,'id_mentor_ways', CHtml::listData(MentorWays::model()->findAll(['order' => 'name asc']), 'id', 'name'), ['class' => 'span12']); ?>
		<?php echo $form->error($model,'id_mentor_ways'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>500, 'class'=>'span12')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'message1'); ?>
		<?php echo $form->textArea($model,'message1',array('rows'=>10, 'class'=>'span12 ckeditor')); ?>
		<?php echo $form->error($model,'message1'); ?>
	</div>

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
            $files = $model->listFiles;
            if (!$model->isNewRecord || count($files)):
        ?>
        <hr />
        Отметьте файлы для удаления:<br />
        <?php
            echo CHtml::checkBoxList('MentorPost[deleteFile][]', '', 
                $files,
                array('labelOptions'=>array('style'=>'display:inline;'))
            );            
        ?>
        <?php
            endif;
        ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']); ?>
	</div>
	
	
	<script type="text/javascript">
        $(document).ready(function() {
            CKEDITOR.replace( '<?php echo CHtml::activeId($model, 'message1'); ?>', {	     
                toolbar: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
                	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', 'PasteCode', '-', 'Undo', 'Redo' ] },
                	
                    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                	{ name: 'insert', items: [ 'Image', 'Flash','File', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                    
                    '/',
                	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
                	
                	'/',
                	{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                	{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                	{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                	{ name: 'others', items: [ '-' ] },
                	{ name: 'about', items: [ 'About' ] }
                ],                
            });  
        });            
            
    </script>

<?php $this->endWidget(); ?>

</div><!-- form -->