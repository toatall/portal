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
                'content'=>'<div id="container_news_day"></div><div class="page-header"><a href="" class="btn btn-primary" style="float:right;">Все новости</a></div>',
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

<?php $this->beginWidget('bootstrap.widgets.TbModal', array(
    'id'=>'modalPreviewNews',
    'htmlOptions'=>array('style'=>'width:90%; margin-left:-45%; margin-top:-3%;'),
)); ?>
<div class="modal-header" style="height1:30px;">
    <a class="close" data-dismiss="modal"><h3 class="text-error">&times;</h3></a>
    <h2 id="modal-title-news-preview"></h2>        
</div>
<div class="modal-body" id="modal-content-news-previwe" style="max-height:70vh;"></div>
<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Закрыть',   
        'type'=>'primary',
        'htmlOptions'=>array('data-dismiss'=>'modal'),
    )); ?>
</div>


<?php $this->endWidget(); ?>

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
	    ajaxNews('<?= Yii::app()->controller->createUrl('news/newsDay') ?>', {}, '#container_news_day');
	    // load news ifns
		ajaxNews('<?= Yii::app()->controller->createUrl('news/newsIfns') ?>', {}, '#container_news_ifns');
	    // load humor
		ajaxNews('<?= Yii::app()->controller->createUrl('news/Humor') ?>', {}, '#container_humor');

	    // then modal news preview close(hide)
		$('#modalPreviewNews').on('hide', function() {
			// clear hash			
			window.history.replaceState({}, document.title, '<?= Yii::app()->controller->createUrl('site/index') ?>');
		});


	    // check url hash
	    url_w = getURLParameter('w');
	    if (url_w!=null)
	    {
		    $('#modalPreviewNews').modal('show');
		    ajaxNews(url_w, {}, '#modal-content-news-previwe');		    
		}
		
	});

	// загрузка новости
	function loadNews(url, title, hash)
	{			
		$('#modal-title-news-preview').html(title);
		ajaxNews(url, {}, '#modal-content-news-previwe');		
		changeUrlParam('w', url);
		return false;
	}
		
</script>

