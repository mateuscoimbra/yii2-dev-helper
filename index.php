<?php

// Carregar autoload PRIMEIRO
require __DIR__ . '/../vendor/autoload.php';

// Carregar dotenv ANTES de tudo
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Validar variáveis obrigatórias do banco
$dotenv->required([
    'DB_HOST',
    'DB_NAME', 
    'DB_USERNAME',
    'OPENAI_API_KEY',
    'GEMINI_API_KEY'
]);

// Validações opcionais com tipos
$dotenv->required('DB_PORT')->isInteger();
$dotenv->required('APP_DEBUG')->isBoolean();

defined('YII_DEBUG') or define('YII_DEBUG', $_ENV['APP_DEBUG'] === 'true');
defined('YII_ENV') or define('YII_ENV', $_ENV['APP_ENV'] ?: 'prod');

// Resto do código Yii2
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

// Verifica se as variáveis estão vindo corretamente
// var_dump($_ENV['APP_DEBUG']);
// var_dump($_ENV['APP_ENV']);
// exit; // evita continuar carregando Yii após debug

(new yii\web\Application($config))->run();