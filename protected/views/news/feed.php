<?php 
    /**
     * @param NewsSearch $model
     */
?>

<?php if (count($model)): ?>

    <?php
        
        foreach ($model as $m)
        {
            $this->renderPartial('/news/_feedRow', array('model'=>$m)); 
        }
    ?>
    
    <?php if (isset($type) && count($model) >= NewsSearch::LIMIT_TOP_NEWS) { ?>
    
        <?php if ($lastId > 0) { ?>
        <div id="content_next_<?= $type ?>">
        	<button id="btn-show-<?= $type ?>" class="btn btn-success btn-large" data-last-id="<?= $lastId ?>"><i class="fas fa-arrow-circle-down"></i> Загрузить еще</button>    	
        </div>
        <?php } ?>
        
        <script type="text/javascript">
    
    		$('#btn-show-<?= $type ?>').on('click', function() {
            	url = urlConcationation('<?= $urlAjax ?>', $('#form-search').serialize());
        		ajaxNews(url, {}, '#container_<?= $type ?>', true);
        		$('#content_next_<?= $type ?>').remove();
        	});
        	
        </script>
    
    <?php } ?>
    
<?php else: ?>
<div class="content">
<h3>Данных не найдено</h3>
</div>
<?php endif; ?>