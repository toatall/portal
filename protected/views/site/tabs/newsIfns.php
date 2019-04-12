<?php
    /* @var $this CController */
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
    Yii::app()->getClientScript()->registerCssFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
?>
<div class="thumbnail" style="padding: 20px;">
    <form class="input-append" id="form-search-news-ifns" autocomplete="off">
        <input type="text" id="input-search-news-ifns" class="span5" placeholder="Поиск по тексту..." />
        <div class="input-prepend">
            <span class="add-on"><i class="icon-search"></i></span>            
        </div>
        <input type="text" id="dateFrom-search-news-ifns" class="datepicker span3" placeholder="Поиск по дате от ..." />
        <div class="input-prepend">
            <span class="add-on"><i class="icon-calendar"></i></span>
            
        </div>
        <input type="text" id="dateTo-search-news-ifns" class="datepicker span3" placeholder="Поиск по дате до ..." />
        <div class="input-prepend">
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>        
        <button type="submit" class="btn btn-primary" id="btn-search-news-ifns">Поиск</button>
        <button type="reset" class="btn">Очистить</button>            
    </form>
</div>
<br />
<div id="container_news_ifns"></div>
<script type="text/javascript">
    
    var url_search_news_ifns = '<?= Yii::app()->controller->createUrl('news/newsIfns', ['team'=>'', 'dateFrom'=>'', 'dateTo'=>'']) ?>';
    
    $('#form-search-news-ifns').on('submit',function() {
        url_search_news_ifns = changeParamUrl(url_search_news_ifns, 'team', $('#input-search-news-ifns').val());
        url_search_news_ifns = changeParamUrl(url_search_news_ifns, 'dateFrom', $('#dateFrom-search-news-ifns').val());
        url_search_news_ifns = changeParamUrl(url_search_news_ifns, 'dateTo', $('#dateTo-search-news-ifns').val());
        ajaxNews(url_search_news_ifns, {}, '#container_news_ifns');
        return false;
    });
    
    jQuery('.datepicker').datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });

</script>