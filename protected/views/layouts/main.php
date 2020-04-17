<?php
/* @var $this CController */
/* @var $content string */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    $cs = Yii::app()->clientScript;
    $baseUrl = Yii::app()->baseUrl;
    /**
     * StyleSHeets
     * @var $cs CClientScript
     */
    $cs->registerCssFile($baseUrl . '/extension/bootstrap3/css/bootstrap.css');

    /**
     * JavaScripts
     */
    $cs
        ->registerCoreScript('jquery', CClientScript::POS_HEAD)
        ->registerScriptFile($baseUrl . '/extension/bootstrap3/js/bootstrap.min.js', CClientScript::POS_HEAD);
    ?>



    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="shortcut icon" href="/css/favicon.png" />

    <?php
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/extension/upButton/query.js');
    ?>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/menu.css?v=07042020" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css?v=07042020" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fontawesome/fontawesome-all.min.css" />
    <?php $cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/main.js?v=17042020&t=12', CClientScript::POS_END); ?>
    <?php
        $cs
        ->registerScriptFile(
            $baseUrl . '/extension/baguetteBox/baguetteBox.min.js', CClientScript::POS_END)
        ->registerCssFile(
            $baseUrl . '/extension/baguetteBox/baguetteBox.min.css');

        $cs
        ->registerScriptFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.js')
        ->registerCssFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.css');
    ?>

</head>
<body>
<div id="div-loader" class="loader loader-default" data-halfs></div>
<div class="wrap">
    <div id="logo-background">
        <div id="logo-image"></div>
        <div style="top: 5px; right: 5px; position: absolute;">
            <a href="<?= $this->createUrl('/site/vov') ?>" data-toggle="popover" data-content='Проект "Помним! Гордимся!"' data-placement="left">
                <img src="/images/War.png" width="200px;" />
            </a>
        </div>
    </div>

    <?php $this->widget('bootstrap.widgets.BsNavbar',array(
        'brandLabel' => false,
        'position' => BsHtml::NAVBAR_POSITION_STATIC_TOP,
        'htmlOptions'=>[
            'id'=>'main-navbar',
            'class'=>'navbar-inner',
            'containerOptions' => [
                'class' => 'fluid-2',
            ],
        ],
        'items'=>[
            [
                'class'=>'bootstrap.widgets.BsNav',
                'items'=> Menu::getTopMenuArray(),
            ]
        ],
    ));

    ?>

    <div class="container-fluid" style="padding-top: 20px;">

        <?php if(isset($this->breadcrumbs)):?>
            <?= BsHtml::breadcrumbs($this->breadcrumbs) ?>
        <?php endif?>

        <div class="col-sm-2 col-md-2" style="padding-left:0;">
            <ul class="dropdown-menu dropdown-menu-main dropdown-menu-wrap">
                <?= Menu::model()->getLeftMenuArray(); ?>
            </ul>
            <?php echo Menu::model()->getLeftMenuAdd(Menu::$leftMenuAdd); ?>
            <div id="container-conference-today"></div>
            <div>
                <ul class="dropdown-menu dropdown-menu-main" style="z-index: 0;">
                    <li class="nav-header">Голосование</li>
                    <li>
                        <p style="padding: 0 15px;" id="container-votes"></p>
                    </li>
                    <li class="divider"></li>
                    <li><a href="<?= Yii::app()->createUrl('vote/index') ?>">Смотреть все</a></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-10 col-md-10">
            <?= $content ?>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-dialog" role="dialog" data-backdrop="static" data-result="false" data-dialog="">
    <div class="modal-dialog modal-dialog-large modal-dialog-super-large" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-label="Close">&times;</span></button>
                <h2 id="modal-title" style="font-weight: bold">Load title...</h2>
            </div>
            <div class="modal-body" id="modal-body">
                Load body...
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">
            <b>Внутренние сайты и сервисы ФНС России:</b>
            <br /><a href="http://portal.tax.nalog.ru" target="_blank">Портал ФНС России</a>
            <br /><a href="http://support.tax.nalog.ru" target="_blank">Портал ФКУ "Налог-Сервис" ФНС России</a>
            <br /><a href="https://support.gnivc.ru" target="_blank">Сайт технической поддержки АО "ГНИВЦ"</a>
            <br /><a href="http://edu.tax.nalog.ru" target="_blank">Образовательный портал ФНС России</a>
            <br /><a href="http://support.tax.nalog.ru/esk/phone/" target="_blank">Телефонный справочник работников ФНС / ФКУ</a>
            <br /><a href="http://wiki.tax.nalog.ru/mw/index.php" target="_blank">Глоссарий ФНС России</a>
            <br /><a href="http://riski.regions.tax.nalog.ru/autorize.html" target="_blank">Реестр рисков</a>
            <br /><a href="http://lk3-usr.tax.nalog.ru/user/auth/index" target="_blank">Кабинет налогоплательщика юридического лица</a>
            <br /><a href="https://rdmz-nlb-nginx.lkfl21.tax.nalog.ru/lkfl-ofc/login" target="_blank">Личный кабинет налогоплательщика — физического лица</a>
            <br /><a href="http://ias.ais3.tax.nalog.ru/ais/" target="_blank">Программный комплекс информационно-аналитической работы</a>
        </p>
        <p class="pull-right">
            <b>Внутренние сервисы Управления:</b>
            <br /><?= CHtml::link('Рекомендуемые браузеры', array('site/browsers')); ?>
            <br /><a href="http://u8600-app045:81" target="_blank">Реестр невзысканных сумм по налоговым проверкам (ВНП, КНП)</a>
            <br /><a href="http://u8600-app045:82" target="_blank">Автоматизированная информационная система "Риски"</a>
            <br /><a href="http://u8600-app045:83" target="_blank">Электронный архив</a>
            <br /><a href="http://u8600-app045:85" target="_blank">Реестр проверок органами государственного контроля и надзора</a>
            <br /><a href="http://u8600-app045:86" target="_blank">Реестр МРГ</a>
            <br /><a href="http://u8600-app045:88" target="_blank">Дистранционный мониторинг</a>
        </p>
    </div>
    <div class="text-center">
        <hr />
        <?php
            $statistic = Log::getTodayStatistic();
        ?>
        Количество пользователей онлайн: <code style="font-size: 15px;"><?= $statistic['countUserOnline'] ?></code>
        <br />Количество пользователей за сегодня: <code style="font-size: 15px;"><?= $statistic['countUserOnToday'] ?></code>
    </div>
    <hr />
    <div class="container">
        <p class="pull-left">© Портал УФНС России по Ханты-Мансийскому автономному округу - Югре 2020</p>
        <p class="pull-right">Работает на <a href="http://www.yiiframework.com/" rel="external">Yii Framework</a></p>
    </div>
</footer>

</body>
</html>
