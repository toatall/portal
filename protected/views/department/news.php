<?php
/**
 * @var $this CController
 * @var $model Department
 * @var $urlNextPage string
 * @var $pagination CPagination
 * @var $type string
 * @var $modelTree Tree
 * @var $newsList array
 */
?>

<?php
    $this->breadcrumbs = [
        'Отделы' => ['department/index'],
        $model->concatened . ' (структура)',
    ];
?>

<div class="content content-color" style="width: 600px;">
	<h1><?= isset($modelTree->name) ? $modelTree->name : $model->concatened; ?></h1>
</div>

<hr />

<div id="container_department" style="margin-top: 20px;"></div>

<script type="text/javascript">

	jQuery(function() {
		ajaxNews('<?= $this->createUrl('department/news', ['idTree'=>$modelTree->id]) ?>', {}, '#container_department');
	});
		
</script>