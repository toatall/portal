<?php
/**
 * @var $this NewsController
 * @var $form BsActiveForm
 */

$form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
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
            <?= BsHtml::link('Восстановить', ['restore', 'id'=>$model->id, 'idTree'=>$modelTree->id], ['class' => 'btn btn-primary']) ?>
            <?= BsHtml::link('Удалить безвозвратно', ['delete', 'id'=>$model->id, 'idTree'=>$modelTree->id], ['class' => 'btn btn-danger', 'confirm' => 'Вы уверены, что хотите удалить эту запись?']) ?>
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
    
	<?php echo $form->textFieldControlGroup($model,'title',array('class'=>'span5','maxlength'=>500)); ?>    
    
    <?php

        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->baseUrl.'/extension/ckeditor/ckeditor/ckeditor.js');

        $assetDatepicker =  new DatepickerAsset();
        $assetDatepicker->register();


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
    
	<?php echo $form->textAreaControlGroup($model,'message1',array('ControlGroups'=>6, 'cols'=>50, 'class'=>'ckeditor')); ?>
    <br />
	<?php echo $form->textAreaControlGroup($model,'message2',array('ControlGroups'=>6, 'cols'=>50, 'class'=>'ckeditor')); ?>
    
    
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
                <?= BsHtml::button('Загрузить другое изображение', ['class' => 'btn btn-default', 'onclick' => 'js:$("#thumbnail_image").toggle();']) ?>
            <?php   endif; ?><br /><br />
            <div class="panel panel-default" id="thumbnail_image" <?php echo (!$model->isNewRecord && ($model->thumbail_image!==''))
                ? 'style="display:none;"' : ''; ?>>
                <div class="panel-body">
                    <?php echo $form->fileField($model, '_thumbail_image'); ?>
                </div>
            </div>
            <br />            
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <?php echo $form->textFieldControlGroup($model,'date_start_pub',array(
            'prepend'=>'<i class="fas fa-calendar-alt"></i>',
            'class' => 'datepicker',
            'groupOptions' => ['class' => 'col-sm-3'],
        )); ?>
    </div>

    <div class="row">
        <?php echo $form->textFieldControlGroup($model,'date_end_pub',array(
            'prepend'=>'<i class="fas fa-calendar-alt"></i>',
            'class' => 'datepicker',
            'groupOptions' => ['class' => 'col-sm-3'],
        )); ?>
    </div>

    <div class="row">
        <?php echo $form->textFieldControlGroup($model,'date_top',[
            'data-type' => 'date',
            'prepend'=>'<i class="fas fa-calendar-alt"></i>',
            'class' => 'datepicker',
            'groupOptions' => ['class' => 'col-sm-3'],
        ]); ?>
    </div>

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

    </script>
	
    <div class="well">
        <h4>Загрузка файлов</h4>
        <?php
            $this->widget('CMultiFileUpload', array(
                'name'=>'files',
                'accept'=>'*',
                'duplicate'=>'Файл уже выбран!',  
                'remove'=>'<i class="glyphicon glyphicon-remove text-danger"></i>',
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
                'remove'=>'<i class="glyphicon glyphicon-remove text-danger"></i>',
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
    
	<?php echo $form->checkBoxControlGroup($model,'flag_enable'); ?>
	
	<?php if (Yii::app()->user->isUFNS && $modelTree->module=='news'): ?>
        <?php echo $form->checkBoxControlGroup($model,'on_general_page'); ?>
        <?php echo $form->textFieldControlGroup($model,'tags'); ?>
        <script type="text/javascript">            
            $('#<?= CHtml::activeId($model, 'tags'); ?>').autocomplete({
                source: '<?= Yii::app()->createUrl('/admin/news/tags') ?>',
                delay: 100
            });
        </script>        
	<?php endif; ?>
    
	<div class="form-actions">
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']); ?>
	</div>

<?php $this->endWidget(); ?>

