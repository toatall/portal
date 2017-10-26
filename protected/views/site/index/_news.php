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

<div style="padding-left: 30px;">
<?php 
    
	foreach ($model as $data)
	{
		echo $this->renderPartial("/news/_indexRow", array("data"=>$data), true);	    
	}
?>
</div>


<br />
<div style="float:right;">
	<?php if (isset($urlAllNews)) { echo $urlAllNews; } ?>
</div>

<?php if (isset($btnUrl)): ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'type'=>'primary',
        'url'=>$btnUrl['url'],
        'label'=>$btnUrl['name'],
        'htmlOptions'=>array(
            'style'=>'float:right',
        ),
    )); ?>
<?php endif; ?>

<?php 
    //print_r($model[count($model)-1]['id']);
?>