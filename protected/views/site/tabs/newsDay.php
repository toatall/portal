<?php
    /* @var $this CController */
    /* @var $model NewsSearch */

    $asset = new DatepickerAsset();
    $asset->register();
?>
<div class="news-container">
    <div class="row">

        <?php $this->renderPartial('application.views.news._search', [
            'model' => new NewsSearch(),
            'idForm' => 'form-search-news-day',
            'actionForm' => $this->createUrl('news/newsDay'),
        ]) ?>

        <div id="container_news_day"></div>

    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {

        $('#form-search-news-day').on('submit',function() {
            ajaxNews($(this).attr('action'), $(this).serialize(), '#container_news_day');
            return false;
        });

    });

</script>