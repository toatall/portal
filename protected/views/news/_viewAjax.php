<i class="icon-calendar"></i> <i><?php echo date('d.m.Y',strtotime($model['date_create'])); ?></i>,
<i class="icon-user"></i> <i><?php echo User::nameByLogin($model['author']); ?></i>,
<i class="icon-heart"></i> <i><?= $model['count_like'] ?></i>,
<i class="icon-comment"></i> <i><?= $model['count_comment'] ?></i>,
<i class="icon-eye-open" title="Просмотров"></i> <i><?= $model['count_visit'] ?></i>,
<br /><i class="icon-home"></i> <i><?= $model['organization_name'] ?></i>
<hr />

<div style="text-align: justify;" class="gallery">
	<?php echo $model['message2']; ?>
</div>

<?php
    // файлы
    if (count($files)): 
?>
<div class="spoiler-wrap">
    <div class="spoiler-head folded clickable unfolded"><i class="icon-file"></i> Файлы</div>
    <div class="spoiler-body" style="display: block;">
    <?php 
        foreach ($files as $file):
    ?>
        <i class="icon-file"></i> <a href="<?php echo $dirFile . $file['file_name']; ?>" target="_blank">
            <?php echo $file['file_name']; ?></a> (<?php echo FileHelper::fileSizeToText($file['file_size']); ?>)<br />    
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
    if (count($images)): 
?>
<div class="spoiler-wrap">
    <div class="spoiler-head folded clickable unfolded"><i class="icon-picture"></i> Изображения</div>
    <div class="spoiler-body class="gallery" style="display: block;">
        <div class="gallery">
        <?php 
            foreach ($images as $image):
        ?>
            <a href="<?php echo $dirImage . $image['image_name']; ?>" alt="<?php echo basename($model['title']); ?>">
                <img src="<?php echo $image['image_name_thumbs']; ?>" class="thumbnail" style="float: left;" />
            </a>
        <?php
            endforeach;
        ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {		
		baguetteBox.run('.gallery');
	});
</script>
<?php
    endif; 
?>

<?php if (Yii::app()->request->isAjaxRequest): ?>
<script type="text/javascript">
	initSpoilers('.spoiler-wrap');
</script>
<?php endif; ?>

<hr />
<?php 
	$this->renderPartial('application.views.like.btn', ['id'=>$model['id']]);
?>

<hr />
<?php 
	$this->renderPartial('application.views.comment.indexAjax', ['id'=>$model['id']]);
?>
