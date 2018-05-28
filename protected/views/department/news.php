<?php
$this->breadcrumbs=array(
	'Отделы' => array('department/index'),
	$model->concatened . ' (структура)',
);

?>

<div class="content content-color" style="width: 600px;">
	<h1><?= isset($modelTree->name) ? $modelTree->name : $model->concatened; ?></h1>
</div>
&nbsp;
<div id="container_news" style="margin-top: 20px;"></div>

<script type="text/javascript">

	jQuery(function() {
		ajaxNews('<?= Yii::app()->controller->createUrl('news/newsTree', ['idTree'=>$modelTree->id]) ?>', {}, '#container_news', false);
	});
		
</script>
<?php 
    //$this->renderPartial('/news/feed', ['model'=>$modelNews, 'urlAjax'=>$urlAjax, 'type'=>$type, 'lastId'=>$lastId]);
?>

</div>
<?php //$this->renderPartial('_index', ['model'=>$modelNews, 'dirFile'=>'', 'dirImage'=>'']); ?>