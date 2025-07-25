<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'sistema-xpto',
    'name' => 'Sistema Xpto',
    'basePath' => dirname(__DIR__),
    'language' => 'pt-BR',
    'sourceLanguage' => 'en-US',
    'timeZone' => 'America/Sao_Paulo',
    'defaultRoute' => 'dashboard/index',
    'layout' => 'main-dashboard', // views/layouts/main-dashboard.php

    'bootstrap' => ['log'],
    
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    
    'components' => [

        'request' => [
            'cookieValidationKey' => 'SUA_CHAVE_SECRETA_AQUI',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],

        'session' => [
            'timeout' => 3600, // 1 hora
            'cookieParams' => ['httponly' => true],
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true, // Desative em produção
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/app.log',
                ],
            ],
        ],

        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [],
        ],

        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'pt-BR',
            'dateFormat' => 'php:d/m/Y',
            'datetimeFormat' => 'php:d/m/Y H:i:s',
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'BRL',
        ],

        'assetManager' => [
            'appendTimestamp' => true, // força recarregar assets se alterados
        ],

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
    ],

    'params' => $params,
];

return $config;
