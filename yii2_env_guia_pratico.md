# Guia Completo: Configurando dotenv no Yii2

## O que é dotenv?
O dotenv é uma biblioteca que carrega variáveis de ambiente de um arquivo `.env` para o `$_ENV` do PHP. Isso nos permite manter configurações sensíveis (como senhas de banco) fora do código versionado.

## Passo 1: Instalação via Composer

Abra o terminal na pasta raiz do seu projeto Yii2 e execute:

```bash
composer require vlucas/phpdotenv
```

Este comando irá:
- Baixar a biblioteca vlucas/phpdotenv
- Adicionar a dependência no seu composer.json
- Atualizar o autoload

## Passo 2: Criar o arquivo .env

Na **raiz do projeto** (mesma pasta do composer.json), crie um arquivo chamado `.env`:

```env
# ===================
# BANCO DE DADOS MYSQL
# ===================
DB_HOST=localhost
DB_PORT=3306
DB_NAME=meubanco
DB_USERNAME=admin
DB_PASSWORD=admin
DB_CHARSET=utf8mb4

# ===================
# APLICAÇÃO
# ===================
APP_NAME=MeuApp
APP_ENV=dev # Pode ser: 'dev' (desenvolvimento), 'prod' (produção), 'test' (testes automatizados)
APP_DEBUG=true # true para desenvolvimento, false para produção
APP_URL=http://localhost:8080

# ===================
# SERVIÇOS EXTERNOS
# ===================
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=meu@email.com
MAIL_PASSWORD=senha_do_email
```

**⚠️ IMPORTANTE:** Nunca versione o arquivo `.env` no Git!

## Passo 3: Configurar o .gitignore

Adicione esta linha no seu arquivo `.gitignore`:

```gitignore
.env
```

## Passo 4: Criar arquivo .env.example

Crie um arquivo `.env.example` (este SIM pode ser versionado):

```env
# Configurações do Banco de Dados
# ===================
# BANCO DE DADOS MYSQL
# ===================
DB_HOST=localhost
DB_PORT=3306
DB_NAME=meubanco
DB_USERNAME=admin
DB_PASSWORD=admin
DB_CHARSET=utf8mb4

# ===================
# APLICAÇÃO
# ===================
APP_NAME=MeuApp
APP_ENV=dev # Pode ser: 'dev' (desenvolvimento), 'prod' (produção), 'test' (testes automatizados)
APP_DEBUG=true # true para desenvolvimento, false para produção
APP_URL=http://localhost:8080

# ===================
# SERVIÇOS EXTERNOS
# ===================
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=meu@email.com
MAIL_PASSWORD=senha_do_email
```

## Passo 5: Carregar o dotenv no Yii2

Edite o arquivo `web/index.php` (para aplicação web):

```php
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
    'OPENAI_API_KEY'
]);

// Validações opcionais com tipos
$dotenv->required('DB_PORT')->isInteger();
$dotenv->required('APP_DEBUG')->isBoolean();

// Resto do código Yii2
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
```

**Para console** (arquivo `yii`):

```php
#!/usr/bin/env php
<?php

// Carregar autoload
require __DIR__ . '/vendor/autoload.php';

// Carregar dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Validar variáveis obrigatórias
$dotenv->required(['OPENAI_API_KEY']);

// Resto do código
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
```

## Passo 6: Usar as variáveis nos arquivos de configuração

### config/db.php
```php
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
```

### config/web.php
```php
<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'sistema-indicadores',
    'name' => 'Sistema de Indicadores',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'vVXkTkcPbRSVhaMP6av8_Ovvb0AMF32X',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@runtime/cache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        // Exemplo para configuração de email
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => ($_ENV['APP_ENV'] ?? 'development') === 'development',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $_ENV['MAIL_HOST'] ?? 'localhost',
                'username' => $_ENV['MAIL_USERNAME'] ?? '',
                'password' => $_ENV['MAIL_PASSWORD'] ?? '',
                'port' => $_ENV['MAIL_PORT'] ?? '587',
                'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
            ],
        ],
        // Exemplo usando variável de ambiente para debug
        'log' => [
            'traceLevel' => ($_ENV['APP_DEBUG'] ?? false) ? (YII_DEBUG ? 3 : 0) : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // === ROTAS CHATGPT EXTRACTOR ===
                'chatgpt-extractor' => 'chatgpt-extractor/index',
                'chatgpt-extractor/extract' => 'chatgpt-extractor/extract',
                'chatgpt-extractor/preview' => 'chatgpt-extractor/preview',
                'chatgpt-extractor/auto-map/<id_variavel:\d+>' => 'chatgpt-extractor/auto-map',
                'chatgpt-extractor/reanalyze/<id:\d+>' => 'chatgpt-extractor/reanalyze',
            ],
        ],
        // ✅ CORRIGIDO: Substituído env() por $_ENV
        'chatgpt' => [
            'class' => 'app\components\ChatGPTService',
            'apiKey' => $_ENV['OPENAI_API_KEY'] ?? '',
            'model' => $_ENV['OPENAI_MODEL'] ?? 'gpt-4',
            'maxTokens' => (int) ($_ENV['OPENAI_MAX_TOKENS'] ?? 4000),
        ],
    ],
    'params' => $params,
];

/*
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}
*/

// ✅ CORRIGIDO: Adicionado ?? false para evitar erro se APP_DEBUG não existir
// Configurações específicas para desenvolvimento
if (($_ENV['APP_DEBUG'] ?? false) && filter_var($_ENV['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN)) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
```

## Passo 7: Usando variáveis em Controllers/Models

```php
<?php

namespace app\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        // Acessando variáveis de ambiente
        $apiKey = $_ENV['GOOGLE_API_KEY'];
        $isDebug = $_ENV['APP_DEBUG'];
        
        // Ou usando com valor padrão
        $timeout = $_ENV['API_TIMEOUT'] ?? 30;
        
        return $this->render('index', [
            'apiKey' => $apiKey,
            'isDebug' => $isDebug
        ]);
    }
}
```

## Passo 8: Validação de Variáveis Obrigatórias

Para garantir que todas as variáveis necessárias estejam definidas:

```php
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
```

## Boas Práticas

### 1. Organize por seções no .env
```env
# ===================
# BANCO DE DADOS MYSQL
# ===================
DB_HOST=localhost
DB_PORT=3306
DB_NAME=meubanco
DB_USERNAME=admin
DB_PASSWORD=admin
DB_CHARSET=utf8mb4

# ===================
# APLICAÇÃO
# ===================
APP_NAME=MeuApp
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8080

# ===================
# SERVIÇOS EXTERNOS
# ===================
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=meu@email.com
MAIL_PASSWORD=senha_do_email

# ===================
# CONFIGURAÇÕES DO CHATGPT
# ===================
OPENAI_API_KEY=sk-proj-sua-chave-api-aqui
OPENAI_MODEL=gpt-4
OPENAI_MAX_TOKENS=4000
```

### 2. Use valores padrão
```php
$timeout = $_ENV['API_TIMEOUT'] ?? 30;
$maxRetries = (int)($_ENV['MAX_RETRIES'] ?? 3);
```

### 3. Crie uma classe helper (opcional)
```php
<?php

namespace app\helpers;

class EnvHelper
{
    public static function get($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
    
    public static function getBool($key, $default = false)
    {
        $value = self::get($key, $default);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    
    public static function getInt($key, $default = 0)
    {
        return (int)self::get($key, $default);
    }
}
```

## Solução de Problemas Comuns

### Erro: "Class 'Dotenv\Dotenv' not found"
- Verifique se executou `composer install`
- Certifique-se que o `require 'vendor/autoload.php'` está antes do carregamento do dotenv

### Variáveis não estão sendo carregadas
- Verifique se o arquivo `.env` está na raiz do projeto
- Confirme que não há espaços ao redor do `=` no arquivo .env
- Use `var_dump($_ENV)` para debugar quais variáveis estão carregadas

### Arquivo .env não é encontrado
```php
// Debugging: mostrar o caminho atual
echo "Procurando .env em: " . __DIR__ . '/../.env';

// Verificar se arquivo existe
if (!file_exists(__DIR__ . '/../.env')) {
    die('Arquivo .env não encontrado!');
}
```

## Resultado Final

Após seguir todos os passos, você terá:
- ✅ Configurações sensíveis fora do código versionado
- ✅ Diferentes ambientes (desenvolvimento, produção) facilmente gerenciáveis
- ✅ Código mais limpo e seguro
- ✅ Facilidade para deploy em diferentes servidores

Agora suas configurações estão seguras e organizadas! 🎉