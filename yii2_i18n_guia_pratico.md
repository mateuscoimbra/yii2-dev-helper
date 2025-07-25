
# 🌍 Guia Prático de Traduções no Yii2 (i18n)

Este guia mostra como implementar um sistema de **traduções multilíngue no Yii2**, usando o diretório `messages/` e a função `Yii::t()`.

---

## 🗂️ Estrutura de Diretórios

```
/messages/
  ├── pt-BR/
  │   ├── app.php
  │   ├── error.php
  │   └── auth.php
  └── en-US/
      ├── app.php
      ├── error.php
      └── auth.php
```

---

## 🔧 Configurando a Aplicação

### `config/web.php`

```php
'language' => 'pt-BR',       // Idioma atual
'sourceLanguage' => 'en-US', // Idioma original dos textos
```

---

## 🧠 Como Usar Traduções no Código

```php
Yii::t('app', 'Welcome');
Yii::t('error', 'Page not found.');
Yii::t('auth', 'Username or password is incorrect.');
```

> O primeiro argumento é o **domínio** (nome do arquivo de tradução).  
> O segundo argumento é a **chave** original do texto, escrita geralmente em inglês.

---

## ✏️ Exemplo de `messages/pt-BR/app.php`

```php
<?php
return [
    'Welcome' => 'Bem-vindo',
    'Logout' => 'Sair',
    'Login' => 'Entrar',
];
```

---

## ⚙️ Traduzindo com parâmetros

```php
Yii::t('app', 'Hello, {name}!', ['name' => 'Mateus']);
```

### `messages/pt-BR/app.php`

```php
'Hello, {name}!' => 'Olá, {name}!',
```

---

## 🔄 Alternar idiomas dinamicamente

```php
Yii::$app->language = 'en-US'; // ou 'pt-BR'
```

Você pode armazenar a escolha do idioma em:
- Cookies
- Sessão (`Yii::$app->session`)
- Banco de dados do usuário logado

---

## 🚫 Ignorar arquivos de tradução no Git

Adicione ao `.gitignore`:

```
/messages/pt-BR/*.php
/messages/en-US/*.php
```

> Versone apenas arquivos de exemplo (ex: `/messages/pt-BR/app.example.php`).

---

## 📦 Boas práticas

- Use inglês como idioma-fonte (`sourceLanguage`)
- Separe traduções por domínio temático (`app`, `error`, `auth`, etc.)
- Prefira `Yii::t()` ao invés de textos diretos no HTML/PHP
- Use ferramentas de extração automática de traduções (ex: `yii message/config`)

---

## 📚 Leitura complementar

- [Guia oficial do Yii2 sobre i18n](https://www.yiiframework.com/doc/guide/2.0/en/tutorial-i18n)
- [Yii Message Command](https://www.yiiframework.com/doc/api/2.0/yii-console-controllers-messagecontroller)

---

Com essas práticas, você transforma seu sistema Yii2 em uma aplicação pronta para **usuários de qualquer idioma** com pouco esforço.
