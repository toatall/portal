<?php
$this->breadcrumbs=array(
	'Отделы' => array('department/index'),	
	$model->concatened,
);

?>

<?php 
	//$this->renderPartial('_view', ['model'=>$model, ]);
	$this->renderPartial('application.views.news._view', ['model'=>$modelNews[0], 'files'=>$dirFile, 'images'=>$dirImage]);
?>