<?php
/**
 * @var $model array
 * @var $dirFile string
 * @var $dirImage string
 * @var $files array
 * @var $images array
 */

$this->breadcrumbs=array(
	Organization::model()->findByPk($model['id_organization'])->name.' (новости)'=>
    array('news/index', 'organization'=>$model['id_organization']),
    $model['title'],    
);

?>

<div class="content content-color">
<?php 
	$this->renderPartial('_view', ['model'=>$model, 'dirFile'=>$dirFile, 'dirImage'=>$dirImage, 'files'=>$files, 'images'=>$images]);
?>

<hr />
<?php 
	$this->renderPartial('application.views.like.btn', ['id'=>$model['id']]);
?>
<hr />
<?php 
	$this->renderPartial('application.views.comment.index', ['id'=>$model['id']]);
?>
</div>