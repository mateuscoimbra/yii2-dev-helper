# Yii2 Dev Utilities — Login & Auth Debug Pack

Scripts de linha de comando para **diagnosticar e corrigir problemas de autenticação** em projetos Yii2. Coloque os arquivos na **raiz do projeto** e execute via terminal (CLI). Ideais para times que perdem tempo caçando soluções repetidas em diferentes fontes.

> Testado com projetos Yii2 que possuem `vendor/` e `config/web.php`. Os scripts **não** alteram o core do Yii2.

---

## 📦 Conteúdo

- `debug-login.php` — *Debug end‑to‑end do fluxo de login* (model, validação, autenticação e sessão).
- `teste-authkey.php` — *Validação da AuthKey* e do `IdentityInterface` do seu `User` model.
- `corrigir-senha.php` — *Regeração segura do hash de senha* de um usuário específico e teste de validação.

Cada script carrega o Yii2 via:

```php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);
```

---

## ✅ Pré‑requisitos

1. PHP CLI instalado (mesma versão usada no servidor local).
2. Dependências instaladas: `composer install` (garanta que a pasta `vendor/` exista).
3. Configuração válida em `config/web.php` (DB, componentes `user`, `request`, etc.).
4. Seu **model de usuário** deve implementar `IdentityInterface` (padrão do Yii2).
5. Ter acesso ao banco de dados configurado no projeto.

---

## 🔍 1) `debug-login.php` — Diagnóstico Completo do Login

**Objetivo:** verificar rapidamente se o problema está na **validação de credenciais**, **Identity**, **User::validatePassword** ou em **camadas de UI/JS/form**.

### Uso
```bash
php debug-login.php
```

### O que o script faz (pipeline típico)
1. Carrega a aplicação (`web.php`).
2. Executa checagens do `User` model (por e‑mail/username).
3. Valida a senha com `validatePassword()`.
4. Tenta autenticar com `Yii::$app->user->login($identity)` ou via `LoginForm->login()`.
5. Imprime **erros detalhados** do model caso a autenticação falhe.

### Quando usar
- Login falha **no navegador**, mas você não sabe se é **backend** ou **frontend**.
- Dúvidas se o problema é **hash de senha**, **sessão**, **CSRF**, **rules()** ou **JavaScript** do formulário.

### Saída esperada (exemplo)
```
===========================================
  DEBUG DE LOGIN - SISTEMA OIA
===========================================

1. Buscando usuário...
   ✅ Usuário encontrado: id=42, email=admin@acme.com

2. Validando senha...
   ✅ OK

3. Testando identity e login...
   ✅ LOGIN BEM-SUCEDIDO!
   O problema não é com a lógica de login.
   Verifique o JavaScript ou o formulário HTML.
```

---

## 🗝️ 2) `teste-authkey.php` — Verificação de AuthKey / Identity

**Objetivo:** isolar problemas relacionados ao **rememberMe** / **authKey** e à implementação de `IdentityInterface` (métodos `findIdentity()`, `getAuthKey()`, `validateAuthKey()` etc.).

### Uso
```bash
php teste-authkey.php
```

### O que o script faz
- Carrega o app e instancia o `User` via `findIdentity()`.
- Verifica se `getAuthKey()` retorna algo não vazio.
- Valida `validateAuthKey()`.
- Confirma se o ciclo de login + remember funciona do lado do servidor.

### Quando usar
- **“Lembra‑me”** (rememberMe) não persiste sessão entre requisições.
- Suspeita de implementações incorretas de `getAuthKey()` / `validateAuthKey()`.
- Cookies de sessão/autenticação parecem ok, mas o server **não reconhece**.

### Saída esperada (exemplo)
```
===========================================
  TESTE DE AUTHKEY - SISTEMA OIA
===========================================

✅ TODOS OS TESTES PASSARAM!
O problema NÃO é com authKey.

Agora teste fazer login pelo navegador.
Se ainda não funcionar, limpe o cache:
  - Feche e abra o navegador
  - Ou use modo anônimo
  - Ou limpe os cookies do localhost
```

---

## 🔐 3) `corrigir-senha.php` — Regerar Hash de Senha (com segurança)

**Objetivo:** corrigir senhas quebradas (hash legado, import errado, migração incompleta) e **confirmar** que o `validatePassword()` volta a funcionar.

### Uso
```bash
php corrigir-senha.php
```

O script:
- Localiza o usuário alvo (por e‑mail/ID — ajuste a regra conforme seu projeto).
- Gera uma **nova senha forte aleatória** (ou usa a que você passar).
- Aplica `setPassword()` / re‑hash com o `Security` do Yii.
- Salva o usuário, **valida** e exibe as credenciais provisórias.

### Saída esperada (exemplo)
```
===========================================
  CORRIGIR SENHA - SISTEMA OIA
===========================================

Testando nova senha...
✅ Validação OK! Senha funcionando corretamente.

Agora você pode fazer login com:
E-mail: admin@acme.com
Senha: Abc-6hPq2_!mZ
```

> **Dica:** troque a senha imediatamente após recuperar o acesso (fluxo “alterar senha” no sistema).

---

## 🧰 Dicas Rápidas & Pitfalls (Yii2 Autenticação)

- **Ambiente CLI vs Web:** você está carregando `web.php` (sessão/cookies ativados). Em alguns cenários de CLI puro, prefira `console.php` com bootstrap do DB apenas.
- **CSRF:** falhas de login só no **navegador** podem ser CSRF/token expirado ou `<form>` com `method="get"` sem intenção.
- **Cookies/Sessão:** cookie bloqueado por domínio/porta errada (ex.: abrindo por `127.0.0.1` vs `localhost`). Limpar cookies costuma resolver.
- **`validatePassword()` quebrado:** verifique se o hash foi migrado corretamente e se usa `Yii::$app->security->generatePasswordHash()`.
- **`rememberMe` não funciona:** confirme `enableAutoLogin => true` no componente `user` e `getAuthKey()`/`validateAuthKey()` funcional.
- **`IdentityInterface`:** garanta as 5 assinaturas:
  - `findIdentity($id)`
  - `findIdentityByAccessToken($token, $type = null)` (se usar API)
  - `getId()`
  - `getAuthKey()`
  - `validateAuthKey($authKey)`
- **Ambientes diferentes:** `.env`/`config` divergentes entre **web** e **CLI** podem apontar para bancos distintos.
- **Rate limiting / Brute force:** filtros de segurança, IP bans ou middlewares podem bloquear login silenciosamente.

---

## 🧪 Checklist de Saúde do Login (copie e cole no seu PR)

- [ ] `User` implementa `IdentityInterface` completo.
- [ ] `Security::generatePasswordHash()` usado para novas senhas.
- [ ] `config/web.php` tem `user` com `identityClass` correto.
- [ ] `enableAutoLogin` e `loginUrl` configurados conforme necessidade.
- [ ] Form de login envia `POST` com `username`/`password` corretos.
- [ ] **Ambiente** do navegador e do CLI apontam para o **mesmo** DB.
- [ ] Cookies/sessão não bloqueados; domínio/porta consistentes.
- [ ] Scripts desta pasta executam **sem erros** no terminal.

---

## 🔧 Personalização mínima (copie para o seu projeto)

Ajuste **se necessário** dentro de cada script:

```php
// Exemplo de como localizar usuário alvo
$email = 'admin@acme.com';        // ou $id = 1;
$user = \app\models\User::findOne(['email' => $email]); // adapte o campo
```

```php
// Regerar senha customizada (opcional)
$novaSenha = getenv('NEW_PASS') ?: Yii::$app->security->generateRandomString(12);
$user->setPassword($novaSenha);
$user->generateAuthKey();
$user->save(false);
```

---

## 🧯 Troubleshooting Rápido

- **“Class 'ActiveForm' not found”** ao rodar views no PHP puro: falta `use yii\widgets\ActiveForm;` **ou** o arquivo não está sendo renderizado dentro de um contexto Yii (controller/view). **Estes scripts são CLI**, não renderizam views.
- **Erro de conexão DB no CLI:** seu `config/web.php` usa credenciais diferentes do `.env`/variáveis do shell. Exporte as variáveis antes de rodar ou use o mesmo `.env` de desenvolvimento.
- **“Headers already sent”**: comum se tentar manipular cookies/sessão no CLI — foque nos métodos do `User` e do `LoginForm`, não em headers.
- **Nada acontece no navegador, mas CLI passa:** o problema está no **frontend** (JS, máscara, name dos inputs, rota errada, CSRF).

---

## 🛡️ Segurança

- Nunca commite **senhas geradas** no repositório/PR.
- Ao usar `corrigir-senha.php`, **troque a senha** ao primeiro login.
- Limite o acesso a esses scripts a **ambientes de desenvolvimento**.
- Evite imprimir dados sensíveis (tokens, hashes) em logs compartilhados.

---

## 🏁 Execução Rápida (copy/paste)

```bash
# Na raiz do projeto Yii2
php debug-login.php
php teste-authkey.php
php corrigir-senha.php
```

Se algum passo falhar, cole a saída no seu PR/issue que o diagnóstico fica imediato.

---

## 📄 Licença e Contribuição

Sinta‑se à vontade para reutilizar e adaptar. Pull Requests com melhorias (checks adicionais, prints mais úteis, variações para `console.php`) são bem‑vindos.

---

**Feito para times Yii2 que precisam de respostas em minutos, não horas.** 🚀
