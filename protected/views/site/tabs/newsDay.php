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
    <form class="input-append" id="form-search-news-day" autocomplete="off">
        <input type="text" id="input-search-news-day" class="span5" placeholder="Поиск по тексту..." />
        <div class="input-prepend">
            <span class="add-on"><i class="icon-search"></i></span>            
        </div>
        <input type="text" id="dateFrom-search-news-day" class="datepicker span3" placeholder="Поиск по дате от ..." />
        <div class="input-prepend">
            <span class="add-on"><i class="icon-calendar"></i></span>
            
        </div>
        <input type="text" id="dateTo-search-news-day" class="datepicker span3" placeholder="Поиск по дате до ..." />
        <div class="input-prepend">
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>        
        <button type="submit" class="btn btn-primary" id="btn-search-news-day">Поиск</button>
        <button type="reset" class="btn">Очистить</button>            
    </form>
</div>
<br />
<div id="container_news_day"></div><!--div class="page-header"><a href="" class="btn btn-primary" style="float:right;">Все новости</a></div-->
<script type="text/javascript">
    
    var url_search_news_day = '<?= Yii::app()->controller->createUrl('news/newsDay', ['team'=>'', 'dateFrom'=>'', 'dateTo'=>'']) ?>';
    
    $('#form-search-news-day').on('submit',function() {
        url_search_news_day = changeParamUrl(url_search_news_day, 'team', $('#input-search-news-day').val());
        url_search_news_day = changeParamUrl(url_search_news_day, 'dateFrom', $('#dateFrom-search-news-day').val());
        url_search_news_day = changeParamUrl(url_search_news_day, 'dateTo', $('#dateTo-search-news-day').val());
        ajaxNews(url_search_news_day, {}, '#container_news_day');
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