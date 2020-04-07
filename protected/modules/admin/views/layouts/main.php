<?php
/**
 * @var $this Controller
 * @var $content string
 */
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="language" content="en" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

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

    <link rel="stylesheet" type="text/css" href="/css/admin/styles.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/menu.css" />
    <link rel="shortcut icon" href="/css/admin/favicon.png" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fontawesome/fontawesome-all.min.css" />
</head>

<body>

<?php $this->widget('bootstrap.widgets.BsNavbar',array(
    'brandLabel' => false,
    'position' => BsHtml::NAVBAR_POSITION_STATIC_TOP,
    'htmlOptions'=>[
        'id'=>'main-navbar',
        'class'=>'navbar-inner',
//        'containerOptions' => [
//            'class' => 'fluid-2',
//        ],
    ],
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.BsNav',
            'items'=>array(
                array('label'=>'Главная', 'url'=>array('/admin/default/index'),'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Портал', 'url'=>array('/site/index')),
                array('label'=>'Администрирование', 'url'=>'#', 'items'=>array(
                    array('label'=>'Пользователи и группы'),
                    array('label'=>'Пользователи', 'url'=>array('/admin/user/admin')),
                    array('label'=>'Группы', 'url'=>array('/admin/group/admin')),
                    array('label'=>'Модули', 'url'=>array('/admin/module/admin')),
                    array('label'=>'Голосование', 'url'=>array('/admin/vote/admin')),
                    '<li class="divider"></li>',
                    array('label'=>'Управление структурой'),
                    array('label'=>'Организации', 'url'=>array('/admin/organization/admin')),
                    array('label'=>'Меню', 'url'=>array('/admin/menu/admin')),
                ), 'visible'=>(!Yii::app()->user->isGuest && Yii::app()->user->admin),
                ),
                array('label'=>'Контент', 'url'=>'#', 'items'=>array(
                    array('label'=>'Структура', 'url'=>array('/admin/tree/admin')),
                    array('label'=>'Отделы', 'url'=>array('/admin/department/admin')),

                ), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Справка', 'url'=>array('/admin/default/help')),
                array('label'=>'Вход', 'url'=>array('/admin/default/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/admin/default/logout'), 'visible'=>!Yii::app()->user->isGuest),

            ),
        ),
        (!Yii::app()->user->isGuest && !empty(Yii::app()->session['organization']) ?
            '<div style="float:right; padding: 10px;">
            НО: <a href="" style="text-underline:none;" data-toggle="modal" data-target="#changeOrganizationModal" >'
            .Yii::app()->session['organization'].'</a>
            </div>' : ''),
    ),
)); ?>

<div class="wrap">
    <div class="container" id="page">

        <?php if(isset($this->breadcrumbs)):?>
            <?= BsHtml::breadcrumbs($this->breadcrumbs) ?>
        <?php endif?>

        <?php if ($this->module->errorLogin === false): ?>
            <?php echo $content; ?>
        <?php else: ?>
            <div class="error">
                <h1>Ошибка!</h1>
                <?= $this->module->errorLogin; ?>
            </div>
        <?php endif; ?>

        <div class="clear"></div>

    </div><!-- page -->

    <footer class="footer">
        <div class="container">
            <p class="pull-left">
                Административная зона портала Управления ФНС России по Ханты-Мансийскому автономному округу - Югре &copy; <?php echo date('Y'); ?>
            </p>
            <p class="pull-right">
                Работает на <a href="http://www.yiiframework.com/">Yii Framework</a>
            </p>
            <br /><br />
        </div>
    </footer>

</div>
<?php  if (!Yii::app()->user->isGuest): ?>
<div class="modal fade" id="changeOrganizationModal" role="dialog" data-backdrop="static" data-result="false" data-dialog="">
    <div class="modal-dialog modal-dialog-large modal-dialog-super-large" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-label="Close">&times;</span></button>
                <h4>Организации</h4>
            </div>
            <div class="modal-body" id="modal-body">
                <?php
                // спсиок организаций со ссылками для изменения
                $modelOrganization = User::userOrganizations();
                $arrayOrganizationChange = array();
                if ($modelOrganization !== null) {
                    foreach ($modelOrganization as $record) {
                        $arrayOrganizationChange[] = [
                            'label' => $record->code . ' - ' . $record->name,
                            'url' => $this->createUrl('/admin/default/changeCode', ['code' => $record->code]),
                            'active' => (Yii::app()->user->model->current_organization == $record->code),
                        ];
                    }
                }
                echo BsHtml::stackedPills($arrayOrganizationChange);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


</body>
</html>
