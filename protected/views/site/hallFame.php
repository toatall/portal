<div class="content content-color">
<?php 
    // скрипты для просмотра изображений //
    Yii::app()->clientScript->registerScriptFile(
    Yii::app()->baseUrl.'/extension/fancybox/lib/jquery.mousewheel-3.0.6.pack.js');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/fancybox/jquery.fancybox.js?v=2.1.5');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/fancybox/helpers/jquery.fancybox-media.js?v=1.0.6');
    Yii::app()->getClientScript()->registerCssFile(
        Yii::app()->baseUrl.'/extension/fancybox/jquery.fancybox.css?v=2.1.5');
    Yii::app()->getClientScript()->registerScript("fancybox", "
        $(document).ready(function() {
            if ($('.fancybox').length)
            {
                $('.fancybox').fancybox();
            }
        });
    ");
?>
<style type="text/css">
    .carousel-indicators {
        padding: 5px;
        background: #777777;
        background: rgba(0, 0, 0, 0.30);       
    }
</style>

<?php

    $this->pageTitle=Yii::app()->name . ' - Доска почета';
    $this->breadcrumbs=array(
    	'Доска почета' => ['/site/hallFame'],
        $year,
    );
?>

<h1>Доска почета</h1>
<hr />


<?php  
/*
    echo TbHtml::beginFormTb(TbHtml::FORM_LAYOUT_HORIZONTAL, '/site/happyBirthday', 'get');
    echo TbHtml::dropDownListControlGroup('year', $year, $yearList, ['id'=>'years', 'label'=>'Год', 'onchange'=>'this.form.submit();']);
    echo TbHtml::endForm();
    */
?>

<?php 
    if (!$photoFiles):
?>
	<div class="alert alert-danger">Данных за <?= $year ?> год не найдено!</div>
<?php 
    else: 
        echo TbHtml::carousel($photoFiles);
	endif; 
?>
</div>