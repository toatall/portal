<?php

if (isset($breadcrumbs))
{
    $this->breadcrumbs = $breadcrumbs;
}

?>

<div class="alert alert-block">
	<h2 class="heading">Проект "ПОМНИМ! ГОРДИМСЯ!"</h2>
	<h3>Посвященный 75-летию Победы в Великой Отечественной войне 1941-1945 годов</h3>
</div>
<div id="container_news"></div>

<script type="text/javascript">
    jQuery(function() {
        ajaxNews('<?= Yii::app()->controller->createUrl('news/tagNews', array('q'=>75)) ?>', {}, '#container_news');
        $('#page').css('background', '#edeef0');
        $('body').css('background', 'url(/images/hello_html_m25590707.jpg)');
        $('#page').css('background', 'url(/images/hello_html_m25590707.jpg)');
        $('#war_container').hide();
    });

</script>
<style type="text/css">
    #vov_container {
    	position:absolute;
    	top:20px;
    	right: 10px;
    	text-align: right;	
    	width:100%;    	
    	z-index:99;	
    	color: white;        
    }
</style>

<div id="vov_container" style="width: 300px; height: 50px;">	
	<img src="/images/war_logo.png" />
</div>