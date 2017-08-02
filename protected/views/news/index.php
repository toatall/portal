<?php




if (isset($breadcrumbs))
{
	$this->breadcrumbs = $breadcrumbs;
}

?>

<style type="text/css">
    #news-grid, #news-grid .summary{
        top: 0;
        padding-top: 0;
        margin-top: 0;
        margin-bottom: 0;
    }
    .filters td {
        padding: 0;
    }
    #news-grid table td {
        padding: 0;
        border: 0;
    }
    ul.yiiPager .first, ul.yiiPager .last {
    	display: inline;
    } 
</style>

<?php 
	$this->widget('bootstrap.widgets.TbGridView',array(
		'id'=>'news-grid',
		'ajaxUpdate'=>false,
		'dataProvider'=>$model,
		'hideHeader'=>true,
		'summaryText'=>'',
		'columns'=>array(
			array(
				'value'=>'Yii::app()->getController()->renderPartial("application.views.news._indexRow",array("data"=>$data), true)',
				'type'=>'html',
			),
		),
		'pager'=>array(
			'class'=>'bootstrap.widgets.TbPager',
			'displayFirstAndLast'=>true,
		),
				
	));
?>
