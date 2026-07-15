# 🚀 Guia Yii2 Basic + Docker (Para Quem Não Nasceu na Era do Docker)

Este guia foi criado especialmente para desenvolvedores seniores e profissionais experientes que estão migrando de ambientes tradicionais (XAMPP, WampServer, PHP/Composer instalados diretamente na máquina) para o mundo moderno de **containers isolados**. 

Se você não quer (ou não pode) "sujar" o seu sistema operacional instalando múltiplas versões do PHP, Composer ou MySQL locais, este guia é para você! Faremos tudo usando **apenas o Docker**, do início ao fim.

---

## 🛠️ O que vamos construir?
* Uma instalação limpa do **Yii2 Basic Framework**.
* Um ambiente local completo rodando **Apache + PHP 8.2**.
* Banco de dados **MariaDB** (compatível e mais leve que o MySQL).
* **Adminer**: Uma ferramenta web de gerenciamento de banco de dados extremamente rápida e leve (substituto moderno, limpo e de arquivo único para o pesado phpMyAdmin).

---

## 📋 Pré-requisitos
Você só precisa de **uma** única ferramenta instalada na sua máquina:
1. **Docker Desktop** (que já inclui o comando `docker compose` por padrão).
   * [Download do Docker para Windows/macOS/Linux](https://www.docker.com/products/docker-desktop/)

---

## 🚶‍♂️ Passo a Passo "For Dummies"

### Passo 1: Criar a pasta e baixar o Yii2 (Usando Docker como "Instalador")
Como você não tem o Composer na sua máquina, usaremos um container oficial e temporário do Composer apenas para baixar o Yii2 para a sua máquina.

1. Abra o seu terminal (Prompt de Comando, PowerShell ou Terminal do Linux/macOS).
2. Navegue até a pasta onde costuma guardar seus projetos (ex: `cd ~/Projetos` ou `cd C:\Projetos`).
3. Execute o comando abaixo (ele vai baixar o Yii2 na pasta `meu-projeto-yii` e depois o container se auto-destruirá):

```bash
# Se você estiver no Linux ou macOS:
docker run --rm -v $(pwd):/app -w /app composer create-project --prefer-dist yiisoft/yii2-app-basic meu-projeto-yii

# Se você estiver no Windows (PowerShell):
docker run --rm -v ${PWD}:/app -w /app composer create-project --prefer-dist yiisoft/yii2-app-basic meu-projeto-yii

# Nota: Se estiver no Windows (PowerShell), use ${PWD} em vez de $(pwd).
docker run --rm -v $(pwd):/app -w /app composer create-project --prefer-dist yiisoft/yii2-app-basic meu-projeto-yii
docker run --rm -v %cd%:/app -w /app composer create-project --prefer-dist yiisoft/yii2-app-basic meu-projeto-yii
```

---

### Passo 2: Ajustar permissões de arquivos (Apenas para usuários de Linux/macOS)
Como o container do Composer rodou como `root`, alguns arquivos podem ter sido criados sem permissão de escrita para o seu usuário do dia a dia. Resolva isso facilmente:

```bash
cd meu-projeto-yii
sudo chown -R $USER:$USER .
```

---

### Passo 3: Configurar o Docker Compose personalizado (Com Adminer!)
O Yii2 Basic já vem com um arquivo `docker-compose.yml` padrão, mas nós vamos **substituí-lo** para adicionar o **Adminer** e configurar o banco de dados de forma simples e direta.

1. Entre na pasta do projeto recém-criado (`cd meu-projeto-yii`).
2. Abra o arquivo `docker-compose.yml` existente em seu editor de código (VS Code, PHPStorm, Notepad++, etc.) e **substitua todo o seu conteúdo** pelo código abaixo:

```yaml
version: '3.8'

services:
  # Servidor Web com PHP + Apache
  web:
    image: yiisoftware/yii2-php:8.2-apache
    ports:
      - "8080:80"
    volumes:
      - .:/app
    environment:
      - PHP_ENABLE_XDEBUG=1
    depends_on:
      - db

  # Banco de Dados MariaDB (Substituto leve e moderno para o MySQL)
  db:
    image: mariadb:10.11
    ports:
      - "3306:3306"
    environment:
      - MYSQL_ROOT_PASSWORD=root_password
      - MYSQL_DATABASE=yii2_db
      - MYSQL_USER=yii2_user
      - MYSQL_PASSWORD=yii2_password
    volumes:
      - db-data:/var/lib/mysql

  # Gerenciador de Banco de Dados (Substituto ultra-leve para o phpMyAdmin)
  adminer:
    image: adminer:latest
    ports:
      - "8081:8080"
    depends_on:
      - db

volumes:
  db-data:
```

---

### Passo 4: Conectar o Yii2 ao Banco de Dados
Agora precisamos dizer ao Yii2 como se conectar ao container do banco de dados que acabamos de definir.

1. Abra o arquivo `config/db.php`.
2. Substitua o conteúdo pela configuração abaixo. Repare que no campo `host` nós usamos o nome do serviço do docker (`db`) ao invés de `localhost` ou `127.0.0.1`:

```php
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=db;dbname=yii2_db', // 'db' é o nome do serviço no docker-compose
    'username' => 'yii2_user',
    'password' => 'yii2_password',
    'charset' => 'utf8',

    // Schema cache (Ativar apenas em ambiente de produção)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
```

---

### Passo 5: Subir os Containers! 🚀
Pronto! Com tudo configurado, execute o comando abaixo na raiz do projeto para baixar as imagens e iniciar os serviços:

```bash
docker compose up -d
```

> **O que esse comando faz?** O `-d` (detached) diz ao Docker para rodar tudo em segundo plano. Ele vai baixar o Apache, o PHP, o MariaDB e o Adminer e colocá-los para rodar de forma integrada.

---

## 🎯 Como testar e acessar o projeto?

Abra o seu navegador de preferência e acesse as seguintes URLs:

* **Sua Aplicação Yii2:** [http://localhost:8080](http://localhost:8080)
* **Gerenciador de Banco de Dados (Adminer):** [http://localhost:8081](http://localhost:8081)

### Como fazer login no Adminer:
Ao acessar a tela do Adminer, preencha os campos exatamente assim:
* **Sistema:** `MySQL`
* **Servidor:** `db` *(Muito importante! Não digite localhost. O host é o nome do serviço no docker)*
* **Utilizador:** `yii2_user`
* **Palavra-passe:** `yii2_password`
* **Base de dados:** `yii2_db`

---

## 💡 Comandos Úteis do Dia a Dia (Cheat Sheet para Seniores)

Como não temos PHP nem Composer na máquina host, rodamos os comandos **dentro** do container que já está ativo. É extremamente simples:

### 1. Executar Migrações do Yii2 (Criar Tabelas)
Sempre que precisar atualizar ou rodar novas migrações:
```bash
docker compose exec web php yii migrate
```

### 2. Instalar Novas Dependências via Composer
Se precisar instalar uma nova biblioteca ou pacote (ex: Kartik-v, Gii extensions, etc.):
```bash
docker compose exec web composer require nome-do-pacote/aqui
```

### 3. Rodar Gerador de Código Gii via Console ou Acessar
O módulo Gii estará disponível em: [http://localhost:8080/gii](http://localhost:8080/gii)  
*(Por padrão, ele já vem configurado para permitir acessos vindos do ambiente local do Docker).*

### 4. Parar a Aplicação temporariamente
```bash
docker compose stop
```

### 5. Parar e Remover os Containers (Sem perder os dados do Banco)
```bash
docker compose down
```
> Seus dados do banco estão seguros porque criamos um `volume` chamado `db-data` que persiste as informações na sua máquina mesmo que os containers sejam destruídos.

---

## 🤝 Contribuições
Sinta-se à vontade para enviar Pull Requests ou abrir Issues se encontrar pontos de melhoria neste guia. O objetivo é ajudar a comunidade de desenvolvedores tradicionais a usufruir da segurança e portabilidade do Docker sem complicação!
