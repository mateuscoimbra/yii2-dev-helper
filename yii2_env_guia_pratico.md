
# 🛠️ Yii2 — Uso de Arquivo `.env` para Configurações Sensíveis e Dinâmicas

Separar configurações sensíveis do código-fonte é uma **boa prática de segurança e organização**. Com `.env`, você pode manter variáveis como credenciais de banco, API keys e outros dados sigilosos fora do repositório Git.

---

## ✅ 1. Instalar dependência de leitura do `.env`

```bash
composer require vlucas/phpdotenv
```

---

## 📁 2. Criar arquivo `.env` na raiz do projeto

```dotenv
YII_DEBUG=true
YII_ENV=dev

APP_NAME="Meu Sistema Yii2"
APP_URL=http://localhost

DB_DSN="mysql:host=localhost;dbname=yii2app"
DB_USER="root"
DB_PASSWORD="secret"

ADMIN_EMAIL=admin@meusistema.com
JWT_SECRET=supersecreta
```

> ⚠️ Não versionar este arquivo no Git:  
> Adicione ao `.gitignore`:
> ```
> /.env
> ```

---

## ⚙️ 3. Criar arquivo `config/env.php`

```php
<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return [
    'YII_DEBUG' => $_ENV['YII_DEBUG'] ?? false,
    'YII_ENV' => $_ENV['YII_ENV'] ?? 'prod',
];
```

Inclua este arquivo no `index.php` e `yii` CLI:

```php
require __DIR__ . '/../config/env.php';
```

---

## 🧩 4. Atualizar `config/db.php`

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => $_ENV['DB_DSN'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8mb4',
];
```

---

## 🌐 5. Usar variáveis do `.env` em qualquer lugar

```php
Yii::$app->params['adminEmail'] = $_ENV['ADMIN_EMAIL'];
$secret = $_ENV['JWT_SECRET'];
```

---

## 🚀 Dica: Crie um `.env.example` para compartilhar estrutura com o time

```dotenv
# .env.example
YII_DEBUG=true
YII_ENV=dev
DB_DSN="mysql:host=localhost;dbname=yii2app"
DB_USER="root"
DB_PASSWORD="secret"
```

---

## 🔒 Segurança

- Nunca versionar `.env` real
- Usar `.env` por ambiente (dev, staging, produção)
- Armazenar credenciais reais em `.env` no servidor com permissão restrita

---

## 📚 Referências

- https://github.com/vlucas/phpdotenv
- https://12factor.net/config

---

Com `.env`, seu projeto Yii2 fica mais seguro, limpo e pronto para múltiplos ambientes.
