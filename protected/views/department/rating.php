<?php
$this->breadcrumbs=array(
	'Отделы' => array('department/index'),
	$modelTree->name,
);
?>

<h1 class="page-header"><?= $modelTree->name ?></h1>


<style>
	.thumbnails [class*="span"]:first-child {
    	margin-left: 40px;
	}
	.thumb-rating {
		height: 50px;
		overflow: auto;
	}
	.bold ul li a {
		font-weight: bold;
	}
	.stab-content {
		padding-top: 40px;
		border: 1px solid #ddd;
		-webkit-border-radius: 4px;
	    -moz-border-radius: 4px;
	    border-radius: 4px;
	    -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.055);
	    -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.055);
	    box-shadow: 0 1px 3px rgba(0,0,0,0.055);
	    -webkit-transition: all .2s ease-in-out;
	    -moz-transition: all .2s ease-in-out;
	    -o-transition: all .2s ease-in-out;
	}
</style>

<div style="margin-top:20px;">
<?php 	
	
	$flagActive = true;
	$tabs = array();
	foreach ($model as $m)
	{
		$tabs[] = [
			'label'=>$m->name,
			'content'=>'<div id="tab_content_' . $m->id . '"></div>',			
			'active'=>$flagActive,			
		];
		$flagActive=false;

		Yii::app()->clientScript->registerScript('ajax_tab_rating_' . $m->id, 'ajaxGET("' . 
			Yii::app()->controller->createUrl('department/ratingData', ['id'=>$m->id]) . '", {}, "#tab_content_' . $m->id . '"); ', CClientScript::POS_END);
		
	}
	
	$this->widget('bootstrap.widgets.TbTabs', array(	
		'type'=>TbHtml::NAV_TYPE_PILLS,
		'htmlOptions'=>['class'=>'bold'],
		'tabs'=>$tabs,
	));
	
?>

<?php 
		/*
	$tabs = array();	
	foreach ($modelYear as $year)
	{
		$tabs[] = [
			'label'=>$year['rating_year'],			
			'content'=>$this->renderPartial('_ratingData', ['model'=>RatingData::dataRating($model->id, $year['rating_year'], $model->order_asc)], true),
			'active'=>($year['rating_year']==date('Y')),
		];
	}	
?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(	
	'placement'=>'left',
	'htmlOptions'=>['class'=>'bold'],
	'tabs'=>$tabs,
));*/ ?>
</div>