<?php
/* @var $this SiteController */

$this->pageTitle = 'Проект "Помним! Гордимся!"';


$this->breadcrumbs = array(
    $this->pageTitle,
);
?>

<h1 class="head text-danger"><?= $this->pageTitle ?></h1>

<div id="container_vov"></div>


<script type="text/javascript">

	jQuery(function() {
	    ajaxNews('<?= Yii::app()->controller->createUrl('news/vov') ?>', {}, '#container_vov');
	});

</script>

