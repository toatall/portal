
<div class="bs-callout">

<h2><?php /*echo $model->title; ?></h2>
<hr />
<i class="icon-calendar"></i> <i><?php echo date('d.m.Y',strtotime($model->date_create)); ?></i>,
<i class="icon-user"></i> <i><?php echo $model->author; ?></i>
<hr />

<div style="text-align: justify;">
<?php echo $model->message2; ?>
</div>

<?php
    // файлы
    
    if (count($model->files)): 
?>
<div class="spoiler-wrap">
    <div class="spoiler-head folded clickable"><i class="icon-file"></i> Файлы</div>
    <div class="spoiler-body">
    <?php 
        foreach ($model->files as $file):
    ?>
        <i class="icon-file"></i> <a href="<?php echo $dirFile . $file->file_name; ?>" target="_blank">
            <?php echo $file->file_name; ?></a> (<?php echo News::model()->getSizeText($file->file_size); ?>)<br />    
    <?php
        endforeach;
    ?>
    </div>
</div>
<?php
    endif; 
?>


<?php
    // изображения
    if (count($model->images)): 
    
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
        Yii::app()->getClientScript()->registerScript("fancybox", "
            $(document).ready(function() {
            if ($('.fancybox').length)
            {
                $('.fancybox').fancybox();
            } 
            });
        ");
            
?>
<div class="spoiler-wrap">
    <div class="spoiler-head folded clickable"><i class="icon-picture"></i> Изображения</div>
    <div class="spoiler-body">
    <?php 
        foreach ($model->images as $image):
    ?>
        <a href="<?php echo $dirImage . $image->image_name; ?>" class="fancybox" rel="image_news" title="<?php echo basename($model->title); ?>">
            <img src="<?php echo $image->image_name_thumbs; ?>" class="thumbnail" style="float: left;" />
        </a>
    <?php
        endforeach;
    ?>
    </div>
</div>

<?php
    endif; */
?>
</div>