<?php
$this->breadcrumbs=array(
	$modelTree['name'] => array('index'),	
	'Главная',
);

?>

<?php 	
	$this->renderPartial('application.views.news._view', ['model'=>$modelNews[0], 'dirFile'=>$dirFile, 'dirImage'=>$dirImage]);
?>