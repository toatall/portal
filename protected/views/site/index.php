<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php
    $this->widget('bootstrap.widgets.TbTabs', array(
        'id'=>'myTabs',        
        'type'=>'tabs',
        'encodeLabel'=>false,
        'tabs'=>array(           
            array(
                'label'=>'Новость дня',
                'content'=>'<div id="container_news_day"></div><!--div class="page-header"><a href="" class="btn btn-primary" style="float:right;">Все новости</a></div-->',
                'active'=>true,
            ),
            array(
                'label'=>'Новости ИФНС',
                'content'=>'<div id="container_news_ifns"></div>',                
            ),
            array(
                'label'=>'Юмор налоговиков',
                'content'=>'<div id="container_humor"></div>',
            ),
        ),
    
    ));
?>


<!--[if !IE]>-->
<script type="text/javascript">
    jQuery(function() {    	
    	$('a[data-toggle="tab"]').on("show", function(e){
            localStorage.setItem("lastTab", $(e.target).attr("href"));
        });    
        var lastTab = localStorage.getItem("lastTab");
        if (lastTab) {
            $('a[href='+lastTab+']').tab("show");
        } else {
            $('a[data-toggle="tab"]:first').tab("show");
        }
    });
</script>
<!-- <![endif]-->
			
<script type="text/javascript">

	jQuery(function() {
		
		// load news day
	    ajaxNews('<?= Yii::app()->controller->createUrl('news/newsDay') ?>', {}, '#container_news_day', true);
	    // load news ifns
		ajaxNews('<?= Yii::app()->controller->createUrl('news/newsIfns') ?>', {}, '#container_news_ifns', true);
	    // load humor
		ajaxNews('<?= Yii::app()->controller->createUrl('news/Humor') ?>', {}, '#container_humor', true);

	    // check url hash
	    url_w = getURLParameter('w');
	    if (url_w!=null)
	    {
		    $('#modalPreviewNews').modal('show');
		    		  
			ajaxJSON(url_w, {
				title: '#modal-title-news-preview',
				content: '#modal-content-news-previwe'
			});					    		  
		}
		
	});
		
</script>

