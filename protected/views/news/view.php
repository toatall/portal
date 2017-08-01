<?php
$this->breadcrumbs=array(
	//'Новости'=>
	Organization::model()->findByPk($model->id_organization)->name.' (новости)'=>
    array('news/index', 'organization'=>$model->id_organization),
    $model->title,    
);

?>


<?php 
	$this->renderPartial('_view', ['model'=>$model, 'dirFile'=>$dirFile, 'dirImage'=>$dirImage]);
?>

<hr />
<?php 
	$this->renderPartial('application.views.like.btn', ['id'=>$model->id]);
?>
<hr />
<?php 
	$this->renderPartial('application.views.comment.index', ['id'=>$model->id]);
?>
