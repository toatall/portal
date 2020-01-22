<?php
/* @var $this CController */
/* @var $model MentorPost */
/* @var $files array */
/* @var $dirFile string */
?>
<i class="icon-calendar"></i> <i><?php echo date('d.m.Y',strtotime($model->date_create)); ?></i>,
<i class="icon-user"></i> <i><?php echo User::nameByLogin($model->author); ?></i>,
<i class="icon-heart"></i> <i><?= $model->count_like ?></i>,
<i class="icon-comment"></i> <i><?= $model->count_comment ?></i>,
<i class="icon-eye-open" title="Просмотров"></i> <i><?= $model->count_visit ?></i>,
<br /><i class="icon-home"></i> <i><?= $model->org->fullName ?></i>
<hr />

<div style="text-align: justify;" class="gallery">
	<?php echo $model->message1; ?>
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
        <i class="icon-file"></i> <a href="<?php echo $dirFile . $file; ?>" target="_blank">
            <?php echo $file; ?></a><br />    
    <?php
        endforeach;
    ?>
    </div>
</div>
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
	$this->renderPartial('likeBtn', ['id'=>$model->id]);
?>

<hr />
<?php 
	$this->renderPartial('application.views.comment.indexAjax', [
	    'id'=>$model->id,
	    'urlIndex' => Yii::app()->controller->createUrl('comment/indexMentor',array('id'=>$model->id)),
	    'urlUpdate' => Yii::app()->controller->createUrl('comment/updateMentor',array('id'=>'_id_')),
	    'urlForm' => Yii::app()->controller->createUrl('comment/formMentor', ['id'=>$model->id]),
	]);
?>
