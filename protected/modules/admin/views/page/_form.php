<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'news-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    ),
)); ?>

    <?php
        // скрипты для просмотра избражений
        if (!$model->isNewRecord):
        
            // скрипты для просмотра изображений //    
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->baseUrl.'/extension/fancybox/lib/jquery.mousewheel-3.0.6.pack.js');
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->baseUrl.'/extension/fancybox/jquery.fancybox.js?v=2.1.5');            
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5');
            /*Yii::app()->clientScript->registerScriptFile(
                Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7');*/
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7');
            Yii::app()->clientScript->registerScriptFile(
                Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-media.js?v=1.0.6');
            Yii::app()->getClientScript()->registerCssFile(
                Yii::app()->baseUrl.'/extension/fancybox/jquery.fancybox.css?v=2.1.5');
                   
        endif; 
    ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
    
    <h3>Раздел: 
	<?php echo Tree::model()->findByPk($idTree)->name; ?>
    </h3>
    
	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>500)); ?>    
    
    <?php
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/ckeditor/ckeditor/ckeditor.js');
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
        Yii::app()->getClientScript()->registerCssFile(
            Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
    ?>
    
	<?php echo $form->textAreaRow($model,'message1',array('rows'=>6, 'cols'=>50, 'class'=>'ckeditor')); ?>

	<?php echo $form->textAreaRow($model,'message2',array('rows'=>6, 'cols'=>50, 'class'=>'ckeditor')); ?>
    
	<?php echo $form->textFieldRow($model,'date_start_pub',array(
        'style'=>'width:120px;',
        'prepend'=>'<i class="icon-calendar"></i>'        
    )); ?>   
        
	<?php echo $form->textFieldRow($model,'date_end_pub',array(
        'style'=>'width:120px;',
        'prepend'=>'<i class="icon-calendar"></i>'
    )); ?>
    
    <script type="text/javascript">
    
        CKEDITOR.replace( '<?php echo CHtml::activeId($model, 'message1'); ?>', {
	       toolbar: [
                [ 'Source' ],
                [ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                [ 'FontSize', 'TextColor', 'BGColor' ]
	       ]
        });
        
        CKEDITOR.replace( '<?php echo CHtml::activeId($model, 'message2'); ?>', {	     
            toolbar: [
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
            	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
            	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll' ] },
            	//{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
            	
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
            filebrowserBrowseUrl: '<?php echo Yii::app()->baseUrl; ?>/extension/ckeditor/ckfsys-master/browser/default/browser.html?Connector=/extension/ckeditor/ckfsys-master/connectors/php/connector.php',
            filebrowserImageBrowseUrl: '<?php echo Yii::app()->baseUrl; ?>/extension/ckeditor/ckfsys-master/browser/default/browser.html?type=Image&Connector=/extension/ckeditor/ckfsys-master/connectors/php/connector.php',
            filebrowserUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/extension/ckeditor/ckfsys-master/connectors/php/upload.php',
            
        });
        
    
        jQuery('#<?php echo CHtml::activeId($model, 'date_end_pub'); ?>').datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0            
        }); 
        jQuery('#<?php echo CHtml::activeId($model, 'date_start_pub'); ?>').datepicker({
            'format':'dd.mm.yyyy',
            'autoclose':'true',
            'todayBtn':'linked',
            'language':'ru',
            'weekStart':0            
        });   
            
    </script>
	
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
            $files = $model->getListFiles($model->id, $idTree, true);
            if (!$model->isNewRecord || count($files)):
        ?>
        <hr />
        Отметьте файлы для удаления:<br />
        <?php
            echo CHtml::checkBoxList('News[deleteFile][]', '', 
                $files,
                array('labelOptions'=>array('style'=>'display:inline;'))
            );            
        ?>
        <?php
            endif;
        ?>
    </div>
    
    <div class="well">
        <h4 class="header">Загрузка изображений (галерея)</h4>
        <?php
            $this->widget('CMultiFileUpload', array(
                'name'=>'images',
                'accept'=>'jpg|jpeg|gif|bmp|png',
                'duplicate'=>'Этот файл уже выбран!',  
                'denied'=>'Недопустимый тип файла. Разрешены изображения следующих форматов: jpg, jpeg, gif, bmp, png',
                'remove'=>'<i class="icon-remove"></i>',              
            ));        
        ?>
        <?php
            $files = $model->getListImages($model->id, $idTree, true);
            if (!$model->isNewRecord || count($files)):
        ?>
        <hr />
        Отметьте изображения для удаления:<br />
        <?php
            echo CHtml::checkBoxList('News[deleteImage][]', '',                 
                $files,
                array('labelOptions'=>array('style'=>'display:inline;'))
            );            
        ?>
        <?php
            endif;
        ?>  
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {
            if ($('.fancybox').length)
            {
                $('.fancybox').fancybox();
            } 
        });
    </script>
    
	<?php echo $form->checkBoxRow($model,'flag_enable'); ?>
    
    <?php if (!Tree::model()->findByPk($idTree)->use_tape): ?>
        <?php echo $form->checkBoxRow($model,'general_page'); ?>        
    <?php endif; ?>
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
