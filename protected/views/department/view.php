<?php

$breadcrumbsTemp = (isset($breadcrumbsTreePath) ? $breadcrumbsTreePath : []);
$this->breadcrumbs = array_merge(
    [
        'Отделы' => array('department/index'),
        $model->concatened => array('department/view','id'=>$model->id),
    ],
    $breadcrumbsTemp);


if (is_array(end($this->breadcrumbs)))
{
    end($this->breadcrumbs);
    $key = key($this->breadcrumbs);
    array_pop($this->breadcrumbs);
    $this->breadcrumbs = array_merge($this->breadcrumbs, [$key]);
}

?>
<div class="content content-color">
<?php 	    
	$this->renderPartial('application.views.news._view', [
	    'model'=>$modelNews[0], 
	    'dirFile'=>$dirFile, 
	    'dirImage'=>$dirImage,
	    'files'=>$files,
	    'images'=>$images,
	]);
?>
</div>