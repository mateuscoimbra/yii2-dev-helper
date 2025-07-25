<?php

// db.php - Conexão com base no .env (via phpdotenv)

return [
    'class' => 'yii\db\Connection',
    'dsn' => $_ENV['DB_DSN'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8mb4',
];