<?php
$this->breadcrumbs=array(
		$modelTree->name => ['page/index', 'page'=>$modelTree->param1],
		$model->organization->name => ['page/index', 'page'=>$modelTree->param1, 'organization'=>$model->id_organization],
		$model->title,
);

?>

<?php 

	$this->renderPartial('../news/_view', ['model'=>$model]);
?>