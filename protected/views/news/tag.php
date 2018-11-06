<?php

if (isset($breadcrumbs))
{
	$this->breadcrumbs = $breadcrumbs;
}

?>

<div id="container_news"></div>

<script type="text/javascript">
    jQuery(function() {
        ajaxNews('<?= Yii::app()->controller->createUrl('news/tagNews', array('q'=>$q)) ?>', {}, '#container_news');
        $('#page').css('background', '#edeef0');
        $('body').css('background', '#edeef0');
    });

</script>