<?php

// uncomment the following to define a path alias
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap4');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Портал УФНС России по ХМАО - Югре',
    //'theme'=>'portal2018',
    // preloading 'log' component
    'preload' => array(
        'log',
    ),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'bootstrap.behaviors.*',
        'bootstrap.helpers.*',
        'bootstrap.widgets.*',
        'application.assets.*',
    ),
    'language' => 'ru',
    'modules' => array(
        'admin' => array(
            'layout' => '/layouts/column2',
        ),
        'zg',
        /*
          'reestrSVT' => array(
          'layout' => '/layouts/column2',
          ),

          'sez' => array(
          'layout' => '/layouts/column2',
          ),
         */

        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'xxx',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1', '10.186.201.34'),
            'generatorPaths' => array(
                'bootstrap.gii',
            ),
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication	
            'class' => 'WebUser',
            'allowAutoLogin' => false,
            'loginUrl' => array('admin/default/login'),
        ),
        'browser' => array(
            'class' => 'ext.browser.CBrowserComponent',
        ),
        'cache' => array(
            'class' => 'CFileCache',
        ),
        /* 'authManager'=>array(
          'class'=>'PAuthManager',
          'defaultRoles'=>array('guest'),
          ), */
        'bootstrap' => array(
            'class' => 'ext.bootstrap4.components.BsApi',
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                // news			
                /*
                  'news/index/<organization:\d+>/<NewsSearch_page:\d+>'=>'news/index',
                  'news/index/<section:\w+>/<organization:\d+>/<NewsSearch_page:\d+>'=>'news/index',
                  'news/index/<section:\w+>/<organization:\d+>'=>'news/index',
                  'news/index/<section:\w+>/<NewsSearch_page:\d+>'=>'news/index',
                  'news/index/<section:\w+>'=>'news/index',
                 */

                //'news/index/<section:\w+>' => 'news/index',
                // defaults	
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            //'<module:\w+>/<controller:\w+>/<idTree:\d+>'=>'<module>/<controller>/admin',
            //'<module:\w+>/<controller:\w+>/<id:\d+>/<idTree:\d+>'=>'<module>/<controller>/view',
            ),
            'urlSuffix' => '.html',
            'showScriptName' => false,
        ),
        'db' => require(__DIR__ . '/db.php'),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error',
                    'except' => 'exception.CHttpException.400, exception.CHttpException.403, exception.CHttpException.404',
                    'filter' => 'CLogFilter',
                ),
                // uncomment the following to show log messages on web pages
                array(
                    'class' => 'CWebLogRoute',
                ),
            /**/
            ),
        ),

        'dateHelper' => [
            'class' => DateHelper::class,
        ],

    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'o.trusov.r8600@nalog.ru',
        'siteRoot' => $_SERVER['DOCUMENT_ROOT'],
        //'pathFiles' => '/files/{code_no}/{module}/{id}/',
        'pathDocumets' => '/files/{code_no}/{module}/{id}/documents/',
        'pathImages' => '/files/{code_no}/{module}/{id}/image_galery/',
        'miniatureImage' => '/files/{code_no}/{module}/{id}/miniature_image/',
        'pathTelephones' => '/files/telephones',
        'noImage' => '/images/no_image_available.jpeg',
        'pathCardImage' => '/files/{code_no}/department_card_image/',
        // Profiles
        'urlProfiles' => '/images/profiles/',
        'heightImage' => 200,

        'news' => [
            'pageSize' => 10,
        ],


        // настройки модуля обращения граждан (Проект Обращения)
        'zg' => [

            // база электронных адресов органов государственной власти
            'emailGoverment' => [
                // количество строк при отображении базы адресов
                'pageSize' => 100,
                // группы и учетные записи, которым доступно редактирование базы адресов
                'editAccounts' => [
                    'u8600-Obshhij',
                ],
            ],

            // База знаний
            'template' => [
                // количество строк при отображении базы адресов
                'pageSize' => 100,

                // группы и учетные записи, которым доступно редактирование базы адресов
                'editAccounts' => [
                    'u8600-Obshhij',
                ],

                'pathFiles' => '/files/8600/zg/template/{id}/',
            ],
        ],
    ),
);
