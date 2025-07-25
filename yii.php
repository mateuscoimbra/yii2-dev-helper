#!/usr/bin/env php
<?php

// yii - script CLI da aplicação

// Autoload do Composer
require __DIR__ . '/../vendor/autoload.php';

// Carregar variáveis do .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Definições do Yii
defined('YII_DEBUG') or define('YII_DEBUG', filter_var($_ENV['YII_DEBUG'], FILTER_VALIDATE_BOOLEAN));
defined('YII_ENV') or define('YII_ENV', $_ENV['YII_ENV'] ?? 'prod');

// Autoload do Yii
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Carrega e executa o console application
$config = require __DIR__ . '/../config/console.php';
$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);