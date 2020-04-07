<?php
    /* @var $this CController */

    $asset = new DatepickerAsset();
    $asset->register();

?>
<div class="news-container">
    <div class="row">

        <?php $this->renderPartial('application.views.news._search', [
            'model' => new NewsSearch(),
            'idForm' => 'form-search-news-ifns',
            'actionForm' => $this->createUrl('news/newsIfns'),
        ]) ?>

        <div id="container_news_ifns"></div>

    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function() {

        $('#form-search-news-ifns').on('submit',function() {
            ajaxNews($(this).attr('action'), $(this).serialize(), '#container_news_ifns');
            return false;
        });

    });

</script>