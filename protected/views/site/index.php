<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div class="content content-color" style="width: 600px; padding-bottom:30px;">
	<?php $this->renderPartial('/news/_search') ?>	
</div>		

<div id="container_news" style="margin-top: 20px;"></div>

<script type="text/javascript">

	jQuery(function() {
		ajaxNews('<?= Yii::app()->controller->createUrl('news/news') ?>', {}, '#container_news', false);
	});
		
</script>
