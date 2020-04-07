<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>

<?php
    $this->widget('bootstrap.widgets.BsNavs', array(
        'id'=>'myTabs',
        'items'=>array(
            array(
                'label'=>'Новость дня',
                'content'=>$this->renderPartial('tabs/newsDay', null, true),
                'active'=>true,
            ),
            array(
                'label'=>'Новости ИФНС',
                'content'=>$this->renderPartial('tabs/newsIfns', null, true),          
            ),
            array(
                'label'=>'Юмор налоговиков',
                'content'=>$this->renderPartial('tabs/humor', null, true),      
            ),
            array(
                'label'=>'Проект "Помним! Гордимся!"',
                'content'=>$this->renderPartial('tabs/vov', null, true),
            ),
        ),
    
    ));


?>

<?php  /*
$this->beginWidget('bootstrap.widgets.TbModal', array(
    'id'=>'modalPreviewNews',
    'htmlOptions'=>array('style'=>'width:90%; margin-left:-45%; margin-top:-3%;'),
)); ?>
<div style="width:90%; margin-left:-45%; margin-top:-3%;" id="modalPreviewNews" class="modal hide fade">
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
    <?= BsHtml::button('Закрыть', ['class'=>'btn btn-primary', 'data-dismiss'=>'modal']) ?>
</div>


<?php //$this->endWidget();*/ ?>

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
		// load vov
		ajaxNews('<?= Yii::app()->controller->createUrl('news/vov') ?>', {}, '#container_vov');
		
	});

</script>

