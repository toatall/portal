<?php
$this->breadcrumbs=array(
	'Отделы' => array('department/index'),	
	$model->concatened,
);

?>

<?php 	    
	$this->renderPartial('application.views.news._view', [
	    'model'=>$modelNews[0], 
	    'dirFile'=>$dirFile, 
	    'dirImage'=>$dirImage,
	    'files'=>$files,
	    'images'=>$images,
	]);
?>