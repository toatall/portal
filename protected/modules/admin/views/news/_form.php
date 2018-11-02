<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'news-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    ),
)); ?>
    
    <?php if (!$model->isNewRecord && ($model->date_delete!='') && Yii::app()->user->admin): ?>    
        <div class="alert in alert-block fade alert-error">            
            <strong>Данная запись была удалена 
                <?php echo DateHelper::explodeDateTime($model->date_delete); ?>
            </strong>&nbsp;&nbsp;
            <?php 
                $this->widget('bootstrap.widgets.TbButton', array(
                	'url'=>array('restore','id'=>$model->id,'idTree'=>$modelTree->id),
                    'type'=>'primary',		
                	'label'=>'Восстановить',
                )); ?>
            <?php 
                $this->widget('bootstrap.widgets.TbButton', array(                    
                	'url'=>array('delete','id'=>$model->id,'idTree'=>$modelTree->id),
                    'type'=>'danger',		
                	'label'=>'Удалить безвозвратно',
                    'htmlOptions'=>array(
                        'confirm'=>'Вы уверены, что хотите удалить эту запись?',
                    ),
                )); ?>
        </div>                    
    <?php endif; ?>
    
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
	<?php echo $modelTree->name; ?>
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
    
	<?php echo $form->textAreaRow($model,'message1',array('rows'=>6, 'cols'=>50, 'class'=>'ckeditor')); ?>
    <br />
	<?php echo $form->textAreaRow($model,'message2',array('rows'=>6, 'cols'=>50, 'class'=>'ckeditor')); ?>
    
    
    <br />
    <?php if (in_array($modelTree->module, array('news'))): ?>
    <div class="panel panel-default">
        <!-- div class="panel-heading"><h3>Миниатюра</h3></div-->
        <div class="panel-body">
            Миниатюра (изображение):
            <?php   if (!$model->isNewRecord && ($model->thumbail_image!=='')): ?>
                <a href="<?php echo $model->thumbail_image; ?>" class="fancybox">
                    <?php echo basename($model->thumbail_image); ?>
                </a> 
                &nbsp;&nbsp;
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType'=>'button',			
        			'label'=>'Загрузить другое изображение',
                    'htmlOptions'=>array(
                        'onclick'=>'js:$("#thumbail_image").show();'
                    ),
        		)); ?>                       
            <?php   endif; ?><br />
            <div id="thumbail_image"<?php echo (!$model->isNewRecord && ($model->thumbail_image!=='')) 
                ? 'style="display:none;"' : ''; ?>>
            <?php echo $form->fileField($model, '_thumbail_image'); ?>
            </div>
            <br />            
        </div>
    </div>
    <?php endif; ?>
    
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
            	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', 'PasteCode', '-', 'Undo', 'Redo' ] },
            	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll' ] },
            	
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
            $files = $model->listFiles($model->id, $modelTree->id, true);
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
            $files = $model->getListImages($model->id, $modelTree->id, true);
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
	
	<?php if (Yii::app()->user->isUFNS && $modelTree->module=='news'): ?>
        <?php echo $form->checkBoxRow($model,'on_general_page'); ?>
        <?php echo $form->textFieldRow($model,'tags'); ?>
        <script type="text/javascript">            
            $('#<?= CHtml::activeId($model, 'tags'); ?>').autocomplete({
                source: '<?= Yii::app()->createUrl('/admin/news/tags') ?>',
                delay: 100
            });
        </script>        
	<?php endif; ?>
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

