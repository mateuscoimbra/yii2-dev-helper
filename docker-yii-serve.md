```markdown
# 🚀 Yii2 + Docker (Modo Simples com yii serve)

Este guia ensina como rodar aplicações Yii2 usando Docker **sem Nginx e sem Apache**, utilizando o comando:

```

php yii serve

```

👉 Essa é a melhor abordagem para desenvolvimento com Yii2, pois resolve corretamente:
- rotas
- assets (CSS/JS)
- URLs amigáveis

---

# 📁 Estrutura de diretórios

```

/apps/
├── source/
│   └── project-urnammu/
│       ├── web/
│       ├── vendor/
│       ├── yii
│       └── ...
├── data/
│   └── mysql/
└── infra/
└── docker/
└── php.ini

````

---

# 📦 Pré-requisitos

- Docker instalado
- Projeto Yii2 (basic)
- Composer já executado:

```bash
composer install
````

---

# 🐘 1. Criar o Dockerfile

Crie:

```
/apps/source/project-urnammu/Dockerfile
```

Conteúdo:

```dockerfile
FROM php:8.3.30-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.9.5 /usr/bin/composer /usr/bin/composer

WORKDIR /app

EXPOSE 8080

CMD ["php", "yii", "serve", "0.0.0.0", "--port=8080", "--docroot=web"]
```

---

# 🏗️ 2. Build da imagem

```bash
cd /apps/source/project-urnammu

docker build -t php8.3.30-cli-yii2:latest .
```

---

# 🌐 3. Criar network compartilhada

```bash
docker network create shared_net
```

---

# 🐬 4. Subir MySQL

```bash
docker run -d \
  --name mysql \
  --network shared_net \
  -e MYSQL_ROOT_PASSWORD=root123 \
  -e MYSQL_DATABASE=teste \
  -e MYSQL_USER=admin \
  -e MYSQL_PASSWORD=admin \
  -v /apps/data/mysql:/var/lib/mysql \
  -p 3306:3306 \
  mysql:8.0
```

---

# 🌐 5. Subir Adminer

```bash
docker run -d \
  --name adminer \
  --network shared_net \
  -p 8081:8080 \
  adminer
```

Acesse:

```
http://localhost:8081
```

---

# 🚀 6. Subir aplicação Yii2

```bash
docker run -d \
  --name project-urnammu \
  --network shared_net \
  -v /apps/source/project-urnammu:/app \
  -v /apps/infra/docker/php.ini:/usr/local/etc/php/php.ini \
  -p 8082:8080 \
  --env-file /apps/source/project-urnammu/.env \
  php8.3.30-cli-yii2:latest
```

---

# 🌍 7. Acessar aplicação

```
http://localhost:8082
```

---

# 🔗 8. Conectar ao MySQL no Yii2

```php
'db' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=mysql;dbname=teste',
    'username' => 'admin',
    'password' => 'admin',
    'charset' => 'utf8',
],
```

---

# ❗ IMPORTANTE

### ❌ NÃO use localhost no banco

Use:

```
mysql
```

---

# ⚠️ Por que NÃO usar php -S diretamente?

Você pode usar:

```bash
php -S 0.0.0.0:8080 -t web web/index.php
```

Mas no Yii2 isso pode causar:

* ❌ CSS não carregar
* ❌ assets quebrados
* ❌ problemas com rotas

👉 O `yii serve` resolve isso automaticamente.

---

# 🔍 Comandos úteis

## Ver containers

```bash
docker ps
```

## Logs

```bash
docker logs -f project-urnammu
```

## Entrar no container

```bash
docker exec -it project-urnammu sh
```

---

# ❌ Problemas comuns

## Container não sobe

```bash
docker logs project-urnammu
```

---

## Vendor não existe

```bash
composer install
```

---

## Porta ocupada

```bash
-p 8083:8080
```

---

# 🧠 Conceitos importantes

* Imagem → template
* Container → aplicação rodando
* Volume → código local
* Network → comunicação entre containers
* yii serve → servidor ideal para Yii2

---

# ✅ Resultado final

Você terá:

* Yii2 rodando com Docker
* MySQL compartilhado
* Adminer acessível
* Ambiente simples e funcional

---

# 🚀 Próximo nível

* docker-compose
* múltiplos projetos
* ambiente versionado

```

---

## 💡 Conclusão (importante)

Você chegou num setup que:

✔ funciona  
✔ é simples  
✔ respeita o Yii  
✔ não tem overengineering  

👉 Isso é exatamente o que um dev sênior faria para ambiente local.

```