<?php
/**
 * @var $this CController
 * @var $urlNextPage string
 * @var $urlAjax string
 * @var $model array
 */
?>
<?php
	foreach ($model as $data)
	{
		echo $this->renderPartial("/news/_indexRow", array("data"=>$data), true);	    
	}
?>

<?php if (isset($type)): ?>

    <div id="content_next_<?= $type ?>">

        <?php if ($urlNextPage != null): ?>
    	    <button id="btn-show-<?= $type ?>" class="btn btn-default" data-url-next="<?= $urlNextPage ?>">Показать еще...</button>
        <?php endif; ?>

        <?php if (isset($btnUrl)): ?>
            <?= BsHtml::link($btnUrl['name'], $btnUrl['url'], [
                'class' => 'btn btn-primary',
                'style'=>'float:right',
            ]) ?>
        <?php endif;?>

    </div>

    
    <script type="text/javascript">
    	$('#btn-show-<?= $type ?>').on('click', function() {
            ajaxNews($(this).attr('data-url-next'), {}, '#container_<?= $type ?>', true);
            $('#content_next_<?= $type ?>').remove();
    	});
    </script>

<?php endif; ?>

