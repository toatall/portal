<?php


class DatepickerAsset
{
    public function register()
    {
        /* @var $cs CClientScript */
        $cs = Yii::app()->clientScript;

        // Javascript
        $cs->registerScriptFile('/extension/datepicker/js/bootstrap-datepicker.js', CClientScript::POS_HEAD)
           ->registerScriptFile('/extension/datepicker/js/datepicker-kv.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/extension/datepicker/js/locales/bootstrap-datepicker.ru.min.js', CClientScript::POS_HEAD);

        // Css style
        $cs->registerCssFile('/extension/datepicker/css/bootstrap-datepicker3.css')
           ->registerCssFile('/extension/datepicker/css/datepicker-kv.css');

        $cs->registerScript('datePickerScript', "
            jQuery('.datepicker').kvDatepicker({
                'format': 'dd.mm.yyyy',
                'autoclose': true,
                'todayBtn': 'linked',
                'language': 'ru',
                'weekStart': 0,
                'todayHighlight': true
            });        
        ", CClientScript::POS_LOAD);
    }
}