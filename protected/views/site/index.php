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
                'content'=>$this->renderPartial('index/_news', [
                	'model'=>$modelUFNS, 
                	'urlAllNews'=>CHtml::link('Все новости', ['news/index', 'organization'=>'8600'], ['class'=>'btn btn-primary']),
                ], true, false), 
                'active'=>'true'),
            array(
                'label'=>'Новости ИФНС', 
                'content'=>$this->renderPartial('index/_news', [
                	'model'=>$modelIFNS,
                	'urlAllNews'=>CHtml::link('Все новости', ['news/index'], ['class'=>'btn btn-primary']),
                ], true, false)),                
            
            array('label'=>'Юмор налоговиков', 
            	'content'=>$this->renderPartial('index/_news', [
            		'model'=>$modelHumor,
           			'urlAllNews'=>CHtml::link('Все материалы', ['news/index', 'section'=>'Humor'], ['class'=>'btn btn-primary']),             		
            	], true, false)),
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
			
