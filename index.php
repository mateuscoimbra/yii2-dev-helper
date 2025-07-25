<?php

// index.php - Front controller da aplicação

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

// Configuração principal da aplicação
$config = require __DIR__ . '/../config/web.php';

// Instancia e executa a aplicação
(new yii\web\Application($config))->run();