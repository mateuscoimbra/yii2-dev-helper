<?php

// Obter variáveis com valores padrão
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? '3306';
$dbname = $_ENV['DB_NAME'] ?? 'test';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host={$host};port={$port};dbname={$dbname}",
    'username' => $username,
    'password' => $password,
    'charset' => $charset,
    
    // Configurações MySQL otimizadas
    'attributes' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode='STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'",
    ],
    
    // Cache de schema para produção
    'enableSchemaCache' => ($_ENV['APP_ENV'] ?? 'development') === 'production',
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];

/*
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=meubanco',
    'username' => 'admin',
    'password' => 'admin',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
*/