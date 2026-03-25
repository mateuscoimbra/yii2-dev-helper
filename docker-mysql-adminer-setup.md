````markdown
# 🐬 Setup MySQL + Adminer com Docker (Guia Simples)

Este guia mostra como subir rapidamente um ambiente de banco de dados usando Docker com:

- MySQL (container principal)
- Adminer (interface web)
- Network compartilhada
- Persistência de dados no host

---

# 📦 Pré-requisitos

- Docker instalado
- Permissão para rodar docker sem sudo (opcional)

---

# 📥 1. Baixar as imagens

```bash
docker pull mysql:8.0
docker pull adminer
````

---

# 🌐 2. Criar uma network compartilhada

```bash
docker network create shared_net
```

Essa network permite que os containers se comuniquem entre si.

---

# 🐬 3. Subir o MySQL

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

## 🔧 Configuração aplicada

| Item      | Valor            |
| --------- | ---------------- |
| Container | mysql            |
| Root      | root123          |
| Usuário   | admin            |
| Senha     | admin            |
| Banco     | teste            |
| Storage   | /apps/data/mysql |

---

# 📁 Persistência dos dados

Os dados ficam salvos em:

```
/apps/data/mysql
```

⚠️ Importante:

* Não apagar essa pasta se quiser manter os dados
* Se quiser resetar o banco:

```bash
docker rm -f mysql
rm -rf /apps/data/mysql/*
```

---

# 🔍 4. Verificar se o MySQL subiu

```bash
docker ps
```

```bash
docker logs mysql
```

Você deve ver:

```
ready for connections
```

---

# 🧪 5. Testar acesso via terminal

```bash
docker exec -it mysql mysql -uroot -p
```

Senha:

```
root123
```

---

# 🌐 6. Subir o Adminer (porta 8081)

```bash
docker run -d \
  --name adminer \
  --network shared_net \
  -p 8081:8080 \
  adminer
```

---

# 🌍 7. Acessar o Adminer

Abra no navegador:

```
http://localhost:8081
```

---

# 🔗 8. Configurar conexão no Adminer

Preencha:

| Campo    | Valor |
| -------- | ----- |
| Sistema  | MySQL |
| Servidor | mysql |
| Usuário  | admin |
| Senha    | admin |
| Banco    | teste |

⚠️ IMPORTANTE:

* O servidor é **mysql** (nome do container)
* NÃO use localhost

---

# ❌ Problemas comuns

## Erro de acesso (Access denied)

```bash
docker rm -f mysql
rm -rf /apps/data/mysql/*
```

Depois recriar o container

---

## Porta ocupada

Se a 3306 estiver em uso:

```bash
-p 3307:3306
```

---

## Adminer não conecta

Verifique:

* containers na mesma network
* nome do servidor = mysql

---

# 🧠 Conceitos importantes

* **Imagem**: template (mysql:8.0)
* **Container**: instância rodando
* **Volume (-v)**: persistência de dados
* **Network**: comunicação entre containers
* **Porta**: acesso externo

---

# 🚀 Comandos úteis

## Listar containers

```bash
docker ps
```

## Parar container

```bash
docker stop mysql
```

## Remover container

```bash
docker rm -f mysql
```

## Ver logs

```bash
docker logs mysql
```

---

# ✅ Resultado final

Você terá:

* MySQL rodando
* Banco `teste` criado
* Usuário `admin`
* Dados persistidos
* Adminer acessível via browser

---

# 💡 Próximo passo

Evoluir para:

* docker-compose
* múltiplas aplicações PHP/Yii2
* ambiente completo de desenvolvimento

```