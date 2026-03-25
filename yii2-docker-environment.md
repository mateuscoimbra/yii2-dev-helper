# 🚀 Yii2 Docker Environment (PHP + Nginx + MySQL + Adminer)

![Docker](https://img.shields.io/badge/Docker-Ready-blue)
![PHP](https://img.shields.io/badge/PHP-8.2-blueviolet)
![Nginx](https://img.shields.io/badge/Nginx-Latest-green)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![License](https://img.shields.io/badge/License-MIT-lightgrey)

> Ambiente completo, didático e profissional para desenvolvimento com **Yii2 + Docker** no Linux (Ubuntu). Ideal para iniciantes e também pronto para uso em times.

---

# 📑 Índice

* [📌 Visão Geral](#-visão-geral)
* [🧱 Arquitetura](#-arquitetura)
* [🏗️ Estrutura de Diretórios](#️-estrutura-de-diretórios)
* [⚙️ Pré-requisitos](#️-pré-requisitos)
* [🚧 Setup Global (/apps)](#-setup-global-apps)
* [📁 Setup do Projeto](#-setup-do-projeto)
* [🐳 Configuração do Docker](#-configuração-do-docker)
* [🚀 Subindo o Ambiente](#-subindo-o-ambiente)
* [🌐 Acessos](#-acessos)
* [🧰 Adminer (Banco)](#-adminer-banco)
* [🛢️ Banco de Dados](#️-banco-de-dados)
* [📥 Instalando Yii2](#-instalando-yii2)
* [🔌 Conectando Yii2 ao MySQL](#-conectando-yii2-ao-mysql)
* [🔐 Permissões](#-permissões)
* [📜 Logs](#-logs)
* [💾 Backup](#-backup)
* [🧠 Boas Práticas](#-boas-práticas)
* [🐞 Erros Comuns](#-erros-comuns)
* [🚀 Evolução](#-evolução)
* [⭐ Contribuição](#-contribuição)
* [📄 Licença](#-licença)

---

# 📌 Visão Geral

Stack incluída:

* **PHP 8.2 (FPM)** → executa o Yii2
* **Nginx** → servidor web
* **MySQL 8** → banco de dados
* **Adminer** → interface web para gerenciar o banco

Arquitetura de pastas:

* `/apps/source` → código dos projetos
* `/apps/data` → dados persistentes (banco)

---

# 🧱 Arquitetura

```text
[ Browser ]
     ↓
[ Nginx :8080 ]
     ↓
[ PHP-FPM ]
     ↓
[ MySQL ]
     ↑
[ Adminer :8081 ]
```

---

# 🏗️ Estrutura de Diretórios

```bash
/apps
├── source
│   └── meu-projeto
├── data
│   └── mysql
│       └── meu-projeto
```

---

# ⚙️ Pré-requisitos

* Ubuntu 24.04+
* Docker Engine instalado
* Docker Compose (plugin)

---

# 🚧 Setup Global (/apps)

```bash
sudo mkdir -p /apps/source
sudo mkdir -p /apps/data/mysql
sudo chown -R $USER:$USER /apps
```

---

# 📁 Setup do Projeto

```bash
cd /apps/source
mkdir meu-projeto
cd meu-projeto

mkdir -p docker/nginx docker/php
```

---

# 🐳 Configuração do Docker

## docker-compose.yml

```yaml
version: "3.9"

services:
  php:
    build: ./docker/php
    container_name: yii2-php
    volumes:
      - /apps/source/meu-projeto:/var/www/html

  nginx:
    image: nginx:latest
    container_name: yii2-nginx
    ports:
      - "8080:80"
    volumes:
      - /apps/source/meu-projeto:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php

  mysql:
    image: mysql:8.0
    container_name: yii2-mysql
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: yii2
      MYSQL_USER: yii2
      MYSQL_PASSWORD: yii2
    volumes:
      - /apps/data/mysql/meu-projeto:/var/lib/mysql

  adminer:
    image: adminer
    container_name: yii2-adminer
    ports:
      - "8081:8080"
    depends_on:
      - mysql
```

---

## Dockerfile (PHP)

```dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
```

---

## Nginx (default.conf)

```nginx
server {
    listen 80;
    root /var/www/html/web;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass php:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

---

# 🚀 Subindo o Ambiente

```bash
docker compose up -d --build
```

---

# 🌐 Acessos

* App: [http://localhost:8080](http://localhost:8080)
* Adminer: [http://localhost:8081](http://localhost:8081)

---

# 🧰 Adminer (Banco)

Acesse:
👉 [http://localhost:8081](http://localhost:8081)

Use:

* Sistema: MySQL
* Servidor: mysql
* Usuário: yii2
* Senha: yii2
* Banco: yii2

---

# 🛢️ Banco de Dados

| Campo | Valor |
| ----- | ----- |
| Host  | mysql |
| User  | yii2  |
| Pass  | yii2  |
| DB    | yii2  |

---

# 📥 Instalando Yii2

```bash
docker exec -it yii2-php bash
composer create-project yiisoft/yii2-app-basic .
chmod -R 777 runtime web/assets
```

---

# 🔌 Conectando Yii2 ao MySQL

Edite:

```php
config/db.php
```

```php
return [
    'class' => 'yii\\db\\Connection',
    'dsn' => 'mysql:host=mysql;dbname=yii2',
    'username' => 'yii2',
    'password' => 'yii2',
    'charset' => 'utf8',
];
```

---

# 🔐 Permissões

```bash
sudo chown -R $USER:$USER /apps
sudo chmod -R 775 /apps
```

---

# 📜 Logs

```bash
docker compose logs -f
```

---

# 💾 Backup

```bash
tar -czvf backup.tar.gz /apps
```

---

# 🧠 Boas Práticas

* Isolar dados por projeto
* Não compartilhar `/apps/data`
* Versionar imagens Docker
* Usar `.env`

---

# 🐞 Erros Comuns

## ❌ Erro de conexão MySQL

✔ Verifique:

* host = mysql

## ❌ Permissão negada

```bash
sudo chown -R $USER:$USER /apps
```

## ❌ Porta ocupada

Troque:

```yaml
"8080:80"
```

---

# 🚀 Evolução

* Xdebug
* Redis
* CI/CD
* Docker Hub

---

# ⭐ Contribuição

Pull Requests são bem-vindos!

---

# 📄 Licença

MIT