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


<?php 
    $lastId = isset($model[count($model)-1]['id']) ? $model[count($model)-1]['id'] : 0;    
?>


<?php if (isset($type)): ?>

    <?php if ($lastId > 0): ?>
    <div id="content-next-<?= $type ?>">
    	<button id="btn-show-<?= $type ?>" class="btn btn-default" data-last-id="<?= $lastId ?>">Показать еще...</button>
    </div>
    <?php endif;?>
    
    <script type="text/javascript">
    	$('#btn-show-<?= $type ?>').on('click', function() {
    		ajaxNews('<?= Yii::app()->controller->createUrl('news/newsDay', ['id'=>$lastId]) ?>', {}, '#content-next-<?= $type ?>');    		
    	});
    </script>
    
<?php endif;?>



<br />
<!-- div style="float:right;">
	<?php if (isset($urlAllNews)) { echo $urlAllNews; } ?>
</div-->


<div class="page-header">
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
</div>


