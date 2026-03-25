# 🚀 Yii2 Docker Environment for Dummies (PHP + Yii Serve + MySQL + Adminer)

![Docker](https://img.shields.io/badge/Docker-Ready-blue)
![PHP](https://img.shields.io/badge/PHP-Multi--Version-blueviolet)
![Yii2](https://img.shields.io/badge/Yii2-Development-green)
![MySQL](https://img.shields.io/badge/MySQL-Generic-orange)
![License](https://img.shields.io/badge/License-MIT-lightgrey)

> Ambiente de desenvolvimento **didático, prático e reutilizável** para projetos **PHP + Yii2** no Linux usando **Docker**, com a estratégia correta para quem quer:
>
> - manter o **código em `/apps/source`**;
> - manter o **storage do MySQL também dentro de `/apps/source`**;
> - ter **um container PHP por aplicação**, cada um com sua **própria versão de PHP**;
> - usar **um MySQL genérico compartilhado** entre várias aplicações;
> - usar **um Adminer genérico compartilhado**;
> - **não usar Nginx nem Apache**, e sim o **PHP Built-in Server via `php yii serve`**.

---

# 📑 Índice

- [1. Ideia da arquitetura](#1-ideia-da-arquitetura)
- [2. O que muda em relação à estratégia anterior](#2-o-que-muda-em-relação-à-estratégia-anterior)
- [3. Estrutura de diretórios proposta](#3-estrutura-de-diretórios-proposta)
- [4. Pré-requisitos](#4-pré-requisitos)
- [5. Instalação do Docker no Ubuntu](#5-instalação-do-docker-no-ubuntu)
- [6. Colocando seu usuário no grupo docker](#6-colocando-seu-usuário-no-grupo-docker)
- [7. Preparando a estrutura global em `/apps/source`](#7-preparando-a-estrutura-global-em-appssource)
- [8. Criando a network compartilhada](#8-criando-a-network-compartilhada)
- [9. Subindo a infraestrutura compartilhada: MySQL + Adminer](#9-subindo-a-infraestrutura-compartilhada-mysql--adminer)
- [10. Criando uma aplicação Yii2 com PHP próprio](#10-criando-uma-aplicação-yii2-com-php-próprio)
- [11. Exemplo completo da aplicação](#11-exemplo-completo-da-aplicação)
- [12. Como o `yii serve` funciona no Docker](#12-como-o-yii-serve-funciona-no-docker)
- [13. Instalando o Yii2 dentro do container](#13-instalando-o-yii2-dentro-do-container)
- [14. Configurando o banco no Yii2](#14-configurando-o-banco-no-yii2)
- [15. Como acessar no navegador](#15-como-acessar-no-navegador)
- [16. Como criar mais aplicações com outras versões de PHP](#16-como-criar-mais-aplicações-com-outras-versões-de-php)
- [17. Comandos úteis do dia a dia](#17-comandos-úteis-do-dia-a-dia)
- [18. Problemas comuns e soluções](#18-problemas-comuns-e-soluções)
- [19. Boas práticas](#19-boas-práticas)
- [20. Conclusão](#20-conclusão)

---

# 1. Ideia da arquitetura

A arquitetura deste tutorial é propositalmente simples:

- **Infra compartilhada**
  - `mysql-generic`
  - `adminer-generic`
  - `dev_shared_net`

- **Por aplicação**
  - um diretório próprio em `/apps/source/<nome-da-app>`
  - um `Dockerfile` próprio
  - um `docker-compose.yml` próprio
  - um container PHP próprio
  - uma porta própria para acesso no navegador

Ou seja:

- o **MySQL não pertence a uma única aplicação**;
- o **Adminer também não pertence a uma única aplicação**;
- cada projeto Yii2 sobe com seu **próprio container PHP**;
- o servidor web será o próprio **`php yii serve`**, sem Nginx e sem Apache.

Essa abordagem combina bem com desenvolvimento local, especialmente para quem está começando e quer uma estrutura mais fácil de entender e manter.

---

# 2. O que muda em relação à estratégia anterior

O arquivo anterior estava baseado em:

- **PHP-FPM**;
- **Nginx**;
- um desenho mais próximo de “um stack completo por projeto”. fileciteturn2file0

Mas o que você quer de fato é outra estratégia:

1. **Código em `/apps/source/`**;
2. **storage do MySQL também dentro de `/apps/source/`**;
3. **um container PHP por aplicação**, cada um com sua versão;
4. **MySQL genérico compartilhado**;
5. **Adminer genérico compartilhado**;
6. containers mapeados para diretórios locais;
7. **uma network compartilhada** para todos “se enxergarem”;
8. uso do Docker no Linux com seu usuário no grupo `docker`.

Então este README corrige a estratégia e passa a refletir exatamente esse modelo.

---

# 3. Estrutura de diretórios proposta

A estrutura abaixo segue a sua ideia de manter tudo sob `/apps/source`:

```bash
/apps
└── source
    ├── _docker
    │   ├── mysql
    │   │   ├── data
    │   │   ├── initdb
    │   │   └── docker-compose.yml
    │   └── adminer
    │       └── docker-compose.yml
    │
    ├── app-yii2-php82
    │   ├── src
    │   ├── docker
    │   │   └── php
    │   │       └── Dockerfile
    │   ├── .env
    │   └── docker-compose.yml
    │
    └── app-yii2-php74
        ├── src
        ├── docker
        │   └── php
        │       └── Dockerfile
        ├── .env
        └── docker-compose.yml
```

## Explicação da estrutura

### `/apps/source/_docker/mysql/data`
Aqui ficam os arquivos persistidos do MySQL.

### `/apps/source/_docker/mysql/initdb`
Aqui você pode colocar scripts `.sql` que o MySQL deve executar na primeira inicialização.

### `/apps/source/app-yii2-php82/src`
Aqui fica o **código da aplicação**.

### `/apps/source/app-yii2-php82/docker/php/Dockerfile`
Aqui fica o **Dockerfile da aplicação**, com a versão de PHP específica dela.

### `/apps/source/app-yii2-php82/docker-compose.yml`
Aqui fica o Compose da aplicação.

---

# 4. Pré-requisitos

Este tutorial considera:

- Linux Ubuntu;
- Docker Engine instalado;
- Docker Compose Plugin instalado;
- usuário com permissão para usar Docker;
- portas livres para as aplicações e para o Adminer.

Exemplo de portas:

- `8081` → Adminer genérico
- `8080` → app 1
- `8082` → app 2
- `8083` → app 3

---

# 5. Instalação do Docker no Ubuntu

A instalação deve seguir a documentação oficial do Docker para Ubuntu. O Docker recomenda instalar o Docker Engine e depois seguir o pós-instalação para uso sem `sudo`. citeturn480563search6turn480563search0

Depois de instalar, valide:

```bash
docker --version
docker compose version
```

Se ambos responderem corretamente, o Docker está pronto.

---

# 6. Colocando seu usuário no grupo docker

No Linux, é comum querer rodar Docker sem `sudo`. A documentação oficial orienta criar o grupo `docker` e adicionar seu usuário a ele. Ela também alerta que esse grupo concede privilégios equivalentes a root. citeturn480563search0turn480563search3

Execute:

```bash
sudo groupadd docker
sudo usermod -aG docker $USER
newgrp docker
```

Depois teste:

```bash
docker ps
```

Se ainda não funcionar, faça logout/login da sessão e teste de novo.

> **Atenção:** o grupo `docker` dá poder elevado ao usuário. Em ambientes mais sensíveis, vale considerar o modo rootless do Docker. citeturn480563search0turn480563search3

---

# 7. Preparando a estrutura global em `/apps/source`

Crie a estrutura base:

```bash
sudo mkdir -p /apps/source/_docker/mysql/data
sudo mkdir -p /apps/source/_docker/mysql/initdb
sudo mkdir -p /apps/source/_docker/adminer
sudo chown -R $USER:$USER /apps
```

Valide:

```bash
tree /apps/source
```

Se você não tiver o `tree` instalado:

```bash
sudo apt install tree -y
```

---

# 8. Criando a network compartilhada

O Docker permite que containers se comuniquem por redes. A documentação do Compose informa que os serviços em uma mesma network conseguem se alcançar pelo **nome do serviço/container**, e redes nomeadas podem ser reutilizadas entre stacks. citeturn480563search1turn480563search4turn480563search19

Crie uma rede compartilhada chamada `dev_shared_net`:

```bash
docker network create dev_shared_net
```

Confira:

```bash
docker network ls
```

---

# 9. Subindo a infraestrutura compartilhada: MySQL + Adminer

## 9.1 MySQL genérico compartilhado

Crie o arquivo:

```bash
/apps/source/_docker/mysql/docker-compose.yml
```

Conteúdo:

```yaml
services:
  mysql:
    image: mysql:8.0
    container_name: mysql-generic
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: root
      TZ: America/Sao_Paulo
    ports:
      - "3306:3306"
    volumes:
      - /apps/source/_docker/mysql/data:/var/lib/mysql
      - /apps/source/_docker/mysql/initdb:/docker-entrypoint-initdb.d
    networks:
      - dev_shared_net

networks:
  dev_shared_net:
    external: true
```

### Subindo o MySQL

```bash
cd /apps/source/_docker/mysql
docker compose up -d
```

### Validando

```bash
docker ps
```

Você deverá ver um container chamado `mysql-generic`.

---

## 9.2 Adminer genérico compartilhado

Crie o arquivo:

```bash
/apps/source/_docker/adminer/docker-compose.yml
```

Conteúdo:

```yaml
services:
  adminer:
    image: adminer:latest
    container_name: adminer-generic
    restart: unless-stopped
    ports:
      - "8081:8080"
    environment:
      ADMINER_DEFAULT_SERVER: mysql-generic
    networks:
      - dev_shared_net

networks:
  dev_shared_net:
    external: true
```

### Subindo o Adminer

```bash
cd /apps/source/_docker/adminer
docker compose up -d
```

### Acesso

Abra:

```text
http://localhost:8081
```

### Dados de acesso iniciais

- **Sistema:** MySQL
- **Servidor:** `mysql-generic`
- **Usuário:** `root`
- **Senha:** `root`
- **Base:** deixe em branco ou informe a base desejada

---

# 10. Criando uma aplicação Yii2 com PHP próprio

Agora vamos criar uma aplicação chamada `app-yii2-php82` usando PHP 8.2.

Crie as pastas:

```bash
mkdir -p /apps/source/app-yii2-php82/src
mkdir -p /apps/source/app-yii2-php82/docker/php
```

---

# 11. Exemplo completo da aplicação

## 11.1 Dockerfile da aplicação

Crie:

```bash
/apps/source/app-yii2-php82/docker/php/Dockerfile
```

Conteúdo:

```dockerfile
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libpq-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD ["tail", "-f", "/dev/null"]
```

## Por que `php:8.2-cli`?

Porque você **não vai usar PHP-FPM**, nem Nginx, nem Apache.

Você quer rodar o Yii2 com:

```bash
php yii serve
```

Então a imagem correta aqui é uma imagem baseada em **CLI**, não em FPM.

---

## 11.2 Arquivo `.env`

Crie:

```bash
/apps/source/app-yii2-php82/.env
```

Conteúdo:

```env
APP_NAME=app-yii2-php82
CONTAINER_NAME=app-yii2-php82-php
HOST_HTTP_PORT=8080
PHP_VERSION=8.2
MYSQL_HOST=mysql-generic
MYSQL_PORT=3306
MYSQL_DATABASE=app_yii2_php82
MYSQL_USERNAME=root
MYSQL_PASSWORD=root
```

---

## 11.3 docker-compose.yml da aplicação

Crie:

```bash
/apps/source/app-yii2-php82/docker-compose.yml
```

Conteúdo:

```yaml
services:
  php:
    build:
      context: .
      dockerfile: ./docker/php/Dockerfile
    container_name: app-yii2-php82-php
    restart: unless-stopped
    working_dir: /app
    volumes:
      - /apps/source/app-yii2-php82/src:/app
    ports:
      - "8080:8080"
    environment:
      TZ: America/Sao_Paulo
    networks:
      - dev_shared_net
    extra_hosts:
      - "host.docker.internal:host-gateway"
    command: tail -f /dev/null

networks:
  dev_shared_net:
    external: true
```

### Observação importante

Aqui o container sobe “parado”, com `tail -f /dev/null`, para você poder entrar nele e executar os comandos do projeto.

Isso é útil para desenvolvimento, porque:

- você controla quando quer iniciar o `yii serve`;
- você pode instalar dependências primeiro;
- você pode executar Composer, migrations e outros comandos com tranquilidade.

---

# 12. Como o `yii serve` funciona no Docker

O `yii serve` usa o servidor embutido do PHP. O Yii documenta esse modo e mostra que também é possível escolher `host`, `port` e `docroot`. citeturn480563search14turn480563search17

Dentro de container, um detalhe é essencial:

- se você rodar apenas `php yii serve`, normalmente ele vai escutar só em `localhost` interno;
- para acessar do navegador no host Linux, o ideal é escutar em **`0.0.0.0`**.

Use assim:

```bash
php yii serve --host=0.0.0.0 --port=8080
```

Esse ponto é crucial. Sem isso, a porta pode até estar publicada no Docker, mas a aplicação não ficará acessível externamente.

---

# 13. Instalando o Yii2 dentro do container

## 13.1 Suba o container da aplicação

```bash
cd /apps/source/app-yii2-php82
docker compose up -d --build
```

## 13.2 Entre no container

```bash
docker exec -it app-yii2-php82-php bash
```

## 13.3 Instale o Yii2 no diretório `/app`

Se o diretório `src` estiver vazio, rode:

```bash
cd /app
composer create-project --prefer-dist yiisoft/yii2-app-basic .
```

## 13.4 Ajuste permissões necessárias

Ainda dentro do container:

```bash
chmod -R 777 runtime web/assets
```

## 13.5 Inicie o servidor embutido do Yii2

```bash
php yii serve --host=0.0.0.0 --port=8080
```

Agora a aplicação deverá responder no navegador.

---

# 14. Configurando o banco no Yii2

Crie antes a base de dados no MySQL.

Você pode fazer isso pelo Adminer ou via terminal.

## 14.1 Criando a base pelo terminal

No host Linux:

```bash
docker exec -it mysql-generic mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS app_yii2_php82 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## 14.2 Ajustando `config/db.php`

No arquivo:

```php
config/db.php
```

Use:

```php
<?php

return [
    'class' => 'yii\\db\\Connection',
    'dsn' => 'mysql:host=mysql-generic;port=3306;dbname=app_yii2_php82',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8mb4',
];
```

## Importante

O host do banco **não é `localhost`**.

Como a aplicação e o MySQL estão na mesma network Docker, o host correto é o **nome do container/serviço**, neste caso:

```text
mysql-generic
```

A documentação do Docker Compose explica justamente que serviços em uma mesma rede se descobrem pelo nome. citeturn480563search1turn480563search4

---

# 15. Como acessar no navegador

## Aplicação

Abra:

```text
http://localhost:8080
```

## Adminer

Abra:

```text
http://localhost:8081
```

---

# 16. Como criar mais aplicações com outras versões de PHP

Esse é um dos objetivos principais desta arquitetura.

Você pode repetir o mesmo padrão para outras aplicações.

## Exemplo: segunda aplicação com PHP 7.4

Estrutura:

```bash
/apps/source/app-yii2-php74
├── src
├── docker
│   └── php
│       └── Dockerfile
├── .env
└── docker-compose.yml
```

### Dockerfile

```dockerfile
FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD ["tail", "-f", "/dev/null"]
```

### docker-compose.yml

```yaml
services:
  php:
    build:
      context: .
      dockerfile: ./docker/php/Dockerfile
    container_name: app-yii2-php74-php
    restart: unless-stopped
    working_dir: /app
    volumes:
      - /apps/source/app-yii2-php74/src:/app
    ports:
      - "8082:8080"
    environment:
      TZ: America/Sao_Paulo
    networks:
      - dev_shared_net
    command: tail -f /dev/null

networks:
  dev_shared_net:
    external: true
```

Depois:

```bash
cd /apps/source/app-yii2-php74
docker compose up -d --build
docker exec -it app-yii2-php74-php bash
cd /app
composer create-project --prefer-dist yiisoft/yii2-app-basic .
php yii serve --host=0.0.0.0 --port=8080
```

Acesse:

```text
http://localhost:8082
```

Perceba a vantagem:

- app 1 → PHP 8.2
- app 2 → PHP 7.4
- ambas enxergam o mesmo `mysql-generic`
- ambas podem ser administradas pelo mesmo `adminer-generic`

---

# 17. Comandos úteis do dia a dia

## Ver containers em execução

```bash
docker ps
```

## Ver logs do MySQL

```bash
docker logs -f mysql-generic
```

## Ver logs do Adminer

```bash
docker logs -f adminer-generic
```

## Entrar no container da aplicação

```bash
docker exec -it app-yii2-php82-php bash
```

## Executar migration

```bash
docker exec -it app-yii2-php82-php php yii migrate
```

## Instalar dependência via Composer

```bash
docker exec -it app-yii2-php82-php composer require yiisoft/yii2-bootstrap5
```

## Parar aplicação

```bash
cd /apps/source/app-yii2-php82
docker compose down
```

## Subir novamente

```bash
cd /apps/source/app-yii2-php82
docker compose up -d
```

## Reiniciar MySQL compartilhado

```bash
cd /apps/source/_docker/mysql
docker compose restart
```

## Remover container da app

```bash
cd /apps/source/app-yii2-php82
docker compose down
```

## Remover infra compartilhada

```bash
cd /apps/source/_docker/adminer && docker compose down
cd /apps/source/_docker/mysql && docker compose down
```

> Isso não apaga os dados do MySQL, porque o storage está em bind mount local em `/apps/source/_docker/mysql/data`.

---

# 18. Problemas comuns e soluções

## 18.1 Não consigo rodar Docker sem sudo

Confira se seu usuário foi adicionado ao grupo `docker`: citeturn480563search0

```bash
groups
```

Se `docker` não aparecer, refaça:

```bash
sudo usermod -aG docker $USER
newgrp docker
```

---

## 18.2 A aplicação não abre no navegador

Quase sempre é um destes motivos:

1. o container não está rodando;
2. a porta não foi publicada corretamente;
3. o `yii serve` foi iniciado com `localhost` ao invés de `0.0.0.0`;
4. o projeto Yii2 ainda não foi instalado em `/app`.

Valide:

```bash
docker ps
```

E dentro do container:

```bash
ps aux | grep yii
```

---

## 18.3 Erro de conexão com o banco

Confirme no `config/db.php`:

- host = `mysql-generic`
- porta = `3306`
- base = nome da base correta
- usuário/senha válidos

Teste do container da app para o MySQL:

```bash
docker exec -it app-yii2-php82-php bash
mysql -h mysql-generic -uroot -proot
```

---

## 18.4 Porta já está em uso

Troque a porta publicada da aplicação.

Exemplo:

```yaml
ports:
  - "8082:8080"
```

---

## 18.5 Permissão negada em arquivos

Ajuste no host:

```bash
sudo chown -R $USER:$USER /apps
```

Se necessário:

```bash
sudo chmod -R 775 /apps
```

---

## 18.6 O MySQL “sumiu” depois de recriar o container

Se o storage estiver correto em:

```text
/apps/source/_docker/mysql/data
```

os dados continuam lá.

Se perdeu dados, normalmente ocorreu uma destas situações:

- o volume foi apontado para pasta errada;
- a pasta foi apagada manualmente;
- o MySQL inicializou em outro diretório.

---

# 19. Boas práticas

## 19.1 Separe infra compartilhada de aplicação

Não coloque MySQL e Adminer dentro da pasta de uma única app. Mantenha-os em `_docker/`.

## 19.2 Um container PHP por projeto

Isso evita conflito de versão de PHP entre aplicações.

## 19.3 Use portas diferentes por app

Exemplo:

- `8080` → app A
- `8082` → app B
- `8083` → app C

## 19.4 Use nomes claros de containers

Exemplos bons:

- `mysql-generic`
- `adminer-generic`
- `app-yii2-php82-php`

## 19.5 Não use `localhost` para o MySQL dentro do container

Dentro do Docker, use o nome do container/serviço na mesma rede.

## 19.6 Para desenvolvimento, `yii serve` é aceitável

Para estudo, desenvolvimento local e projetos simples, faz sentido.

Mas é importante entender:

- **`yii serve` não é a estratégia ideal de produção**;
- para produção, normalmente você migraria para Nginx/Apache + PHP-FPM.

Aqui, porém, o foco é **desenvolvimento local didático**, então a decisão faz sentido.

## 19.7 Documente a versão de PHP por projeto

No README da própria aplicação, deixe explícito:

- versão do PHP;
- porta publicada;
- nome da base;
- credenciais usadas em desenvolvimento.

---

# 20. Conclusão

A estratégia correta para o seu caso é esta:

- **`/apps/source`** como raiz de tudo;
- **código das aplicações dentro de `/apps/source/<app>/src`**;
- **storage do MySQL dentro de `/apps/source/_docker/mysql/data`**;
- **MySQL genérico compartilhado**;
- **Adminer genérico compartilhado**;
- **network compartilhada `dev_shared_net`**;
- **um container PHP por aplicação**;
- **cada aplicação com sua própria versão de PHP**;
- **sem Nginx e sem Apache**;
- **Yii2 servido com `php yii serve --host=0.0.0.0 --port=8080`**. citeturn480563search14turn480563search17

Essa organização é muito boa para quem está aprendendo Docker e quer evitar uma arquitetura mais complexa logo no começo, sem abrir mão de isolamento entre aplicações e reaproveitamento da infraestrutura compartilhada.

---

# 📄 Licença

MIT
