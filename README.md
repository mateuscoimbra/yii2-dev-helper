# Yii2 Exemplos Comentados 🇧🇷✨

> Repositório com exemplos ultra comentados de componentes Yii2 como `ActiveForm`, `GridView` e `DetailView`, criado para auxiliar programadores iniciantes e acelerar o desenvolvimento com base em boas práticas e reutilização de código.

> 🔒 **Nota:** Este exemplo é apenas para fins didáticos/documentais. Nem todos os atributos coexistem no mundo real, mas o objetivo aqui é **cobrir todas as possibilidades comuns e avançadas** de uso.

---

## 📌 Objetivo 🎯

Este repositório foi criado para ser um **guia prático e direto ao ponto**, com foco em:

- ✅ Exemplos reais e funcionais
- 📝 Código PHP altamente comentado
- 🛠️ Aplicações dos principais widgets e componentes do Yii2
- ⚡ Consulta rápida para quem está começando

---

## 📦 Conteúdo 📚

| Arquivo                               | Descrição                                                                                               |
|---------------------------------------|---------------------------------------------------------------------------------------------------------|
| `yii2_componentes_ultra_completos.md` | Guia detalhado em Markdown com exemplos completos de `ActiveForm`, `GridView` e `DetailView`.           |
| `yii2_snippet_base_completo.php`      | Snippet PHP completo, pronto para copiar e colar no projeto, com um modelo mock para testes.            |
| `mysql_snippet_base_completo.md`      | Guia para iniciantes com os tipos de dados, índices, chaves estrangeiras e constraints                  |

---

## 🧰 Tecnologias ⚙️

- [Yii2 Framework](https://www.yiiframework.com/doc/guide/2.0/pt-br)
- PHP 7.4+
- HTML / Bootstrap (exemplos com classes do Bootstrap)
- Font Awesome (para ícones nos exemplos)

---

## 🚀 Como usar 💡

Clone este repositório e use os arquivos como base nos seus projetos:

```bash
git clone [https://github.com/seu-usuario/yii2-exemplos-comentados.git](https://github.com/seu-usuario/yii2-exemplos-comentados.git)
````

Inclua os exemplos no seu projeto Yii2, adapte conforme seu modelo e contexto. Lembre-se de instalar as dependências do Yii2 via Composer.

-----

# 🧭 Guia de Configuração Yii2 para Aplicações no Brasil

Este projeto utiliza o Yii2 Framework com configurações adaptadas para aplicações brasileiras, incluindo idioma, timezone, formatação local e boas práticas de layout e estrutura.

---

## ⚙️ Estrutura do Projeto

- `config/web.php`: Configurações da aplicação web (entrypoint padrão).
- `config/params.php`: Parâmetros customizados.
- `config/db.php`: Configurações de banco de dados.
- `views/layouts/main-dashboard.php`: Layout principal da aplicação.
- `messages/pt-BR/`: Diretório de traduções (i18n).

---


```
No `app/config/web.php` (ou `web.php`), a variável `$config` aceita diversos *atributos principais*, e dentro de cada seção (`components`, `modules`, etc.), há dezenas de subatributos configuráveis.

---

✅ Principais atributos do array `$config`

Aqui está uma explicação das *principais chaves possíveis* no array de configuração do Yii2:

| Atributo              | Descrição                                                                  |
| --------------------- | -------------------------------------------------------------------------- |
| `id`                  | ID da aplicação. Obrigatório.                                              |
| `basePath`            | Caminho base da aplicação (normalmente `dirname(__DIR__)`).                |
| `name`                | Nome da aplicação. (opcional, aparece em `Yii::$app->name`)                |
| `language`            | Idioma padrão (ex: `pt-BR`, `en-US`).                                      |
| `sourceLanguage`      | Idioma base do sistema (ex: `en-US`).                                      |
| `bootstrap`           | Componentes ou classes que devem ser carregadas no bootstrap da aplicação. |
| `aliases`             | Atalhos de caminhos para diretórios (ex: `@bower`, `@npm`, `@runtime`).    |
| `components`          | Lista de componentes da aplicação (cache, db, mailer, log, etc.).          |
| `modules`             | Lista de módulos registrados na aplicação.                                 |
| `controllerNamespace` | Namespace padrão dos controllers (ex: `app\controllers`).                  |
| `defaultRoute`        | Rota padrão, como `'site/index'`.                                          |
| `layout`              | Layout padrão usado pelos controllers.                                     |
| `params`              | Parâmetros externos, geralmente definidos no `params.php`.                 |
| `runtimePath`         | Caminho para o diretório de arquivos temporários e de log.                 |
| `vendorPath`          | Caminho para o diretório `vendor`.                                         |
| `timeZone`            | Fuso horário da aplicação (`America/Sao_Paulo`, por exemplo).              |

---

✅ Exemplos de componentes comuns (dentro de `'components'`)

Você já usa vários, mas pode configurar outros como:

* `formatter`: para formatação de datas, números, moedas
* `authManager`: para RBAC
* `i18n`: para tradução e internacionalização
* `session`: configurações de sessão
* `assetManager`: para controle de assets (como forçar publish de JS/CSS)
* `view`: configuração da renderização de views
* `response`: cabeçalhos de saída, formatação JSON, etc.

```php
'formatter' => [
    'class' => 'yii\i18n\Formatter',
    'dateFormat' => 'php:d/m/Y',
    'datetimeFormat' => 'php:d/m/Y H:i:s',
    'currencyCode' => 'BRL',
],
```

---

✅ Outros exemplos úteis

```php
'name' => 'Sistema de Indicadores',
'language' => 'pt-BR',
'sourceLanguage' => 'en-US',
'timeZone' => 'America/Sao_Paulo',
'defaultRoute' => 'dashboard/index',
'layout' => 'main-dashboard', // views/layouts/main-dashboard.php
```

---

Claro, Mateus! Abaixo está um `README.md` em Markdown que documenta essas boas práticas e configurações para uma aplicação Yii2 voltada ao contexto brasileiro:

---

````markdown
# 🧭 Guia de Configuração Yii2 para Aplicações no Brasil

Este projeto utiliza o Yii2 Framework com configurações adaptadas para aplicações brasileiras, incluindo idioma, timezone, formatação local e boas práticas de layout e estrutura.

---

## ⚙️ Estrutura do Projeto

- `config/web.php`: Configurações da aplicação web (entrypoint padrão).
- `config/params.php`: Parâmetros customizados.
- `config/db.php`: Configurações de banco de dados.
- `views/layouts/main-dashboard.php`: Layout principal da aplicação.
- `messages/pt-BR/`: Diretório de traduções (i18n).

---

## 🇧🇷 Configuração recomendada (`web.php`)

```php
$config = [
    'id' => 'sistema-indicadores',
    'name' => 'Sistema de Indicadores',
    'basePath' => dirname(__DIR__),
    'language' => 'pt-BR',
    'sourceLanguage' => 'en-US',
    'timeZone' => 'America/Sao_Paulo',
    'defaultRoute' => 'dashboard/index',
    'layout' => 'main-dashboard',

    'bootstrap' => ['log'],

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'components' => [
        'request' => [
            'cookieValidationKey' => 'SUA_CHAVE_SECRETA_AQUI',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'session' => [
            'timeout' => 3600,
            'cookieParams' => ['httponly' => true],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
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
            'rules' => [],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'pt-BR',
            'dateFormat' => 'php:d/m/Y',
            'datetimeFormat' => 'php:d/m/Y H:i:s',
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'BRL',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
    ],

    'params' => $params,
];
````

---

## 🎨 Personalização do Navbar

Para usar uma `NavBar` com cor personalizada (por exemplo, `#001f5d`), aplique uma classe no componente e defina os estilos no CSS:

### PHP

```php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-expand-md custom-navbar fixed-top',
    ],
]);
```

### CSS (`web/css/site.css`)

```css
.custom-navbar {
    background-color: #001f5d !important;
}

.custom-navbar .navbar-brand,
.custom-navbar .nav-link {
    color: #ffffff !important;
}

.custom-navbar .dropdown-menu {
    background-color: #002a80;
    border: none;
}

.custom-navbar .dropdown-item {
    color: #ffffff !important;
}

.custom-navbar .dropdown-item:hover {
    background-color: #003d99;
}
```

---

## 🔐 Segurança

* Gere uma `cookieValidationKey` com segurança:

```php
Yii::$app->security->generateRandomString();
```

* Em produção, defina `'useFileTransport' => false` no `mailer`.

---

## 📦 Dependências e Assets

* Registre corretamente os arquivos CSS/JS em `AppAsset.php`.
* Use `appendTimestamp => true` no `assetManager` para forçar recarregamento de arquivos alterados.

---

## 🗂 Organização Sugerida

```
app/
├── config/
│   ├── web.php
│   ├── db.php
│   └── params.php
├── views/
│   └── layouts/
│       └── main-dashboard.php
├── web/
│   └── css/
│       └── site.css
├── messages/
│   └── pt-BR/
│       ├── app.php
│       └── error.php
```

---

## 🧩 Campos Especiais com inputOptions

Além de passar atributos diretamente em `textInput()`, você também pode usar `inputOptions` diretamente no terceiro parâmetro de `$form->field()`:

```php
$form->field($model, 'nome', [
    'inputOptions' => [
        'id' => 'nome-entrega',
        'class' => 'form-control',
        'autofocus' => true,
    ]
])->textInput(['maxlength' => true, 'placeholder' => "Nome da entrega/produto"]);
```

## 📅 Widget MaskedInput (componente externo)

```php
use yii\widgets\MaskedInput;

$form->field($model, 'data_evento', [
    'template' => '{input}{error}{hint}'
])->widget(MaskedInput::class, [
    'mask' => '99/99/9999',
    'clientOptions' => [
        'alias' => 'datetime',
        'placeholder' => 'dd/mm/aaaa'
    ]
]);
```

## 🖼️ Exibindo Imagens Base64

### Em GridView:
```php
[
    'attribute' => 'imagem_base64',
    'format' => 'raw',
    'value' => fn($model) =>
        "<img src='data:image/jpeg;base64," . $model->imagem_base64 . "' width='70' height='70'/>",
    'filter' => false,
]
```

### Em DetailView:
```php
[
    'attribute' => 'imagem_base64',
    'value' => 'data:image/png;base64,' . $model->imagem_base64,
    'format' => ['image', ['width' => '100', 'height' => '100']]
]
```

## 🎯 Campos HTML5 com ->input('type')

```php
$form->field($model, 'email')->input('email');
$form->field($model, 'data_nascimento')->input('date');
$form->field($model, 'numero')->input('number');
$form->field($model, 'telefone')->input('tel');
$form->field($model, 'url')->input('url');
$form->field($model, 'cor_favorita')->input('color');
$form->field($model, 'arquivo')->input('file');
```

## 👁️ Colunas Condicionalmente Visíveis no GridView

```php
[
    'attribute' => 'campo_secreto',
    'visible' => Yii::$app->user->identity->isAdmin,
    'value' => fn($model) => $model->campo_secreto,
]
```

## 🔐 Segurança e Autenticação Avançada (JWT)

Para projetos com APIs RESTful protegidas por tokens, veja:

- [sizeg/yii2-jwt no GitHub](https://github.com/sizeg/yii2-jwt/)
- [Tutorial oficial Yii2 JWT](https://www.yiiframework.com/wiki/2568/jwt-authentication-tutorial)


```


## 🤝 Contribuições 💖

Contribuições são bem-vindas\! Se você tem exemplos úteis, sugestões ou melhorias, sinta-se à vontade para abrir um Pull Request ou Issue.

-----

## 📄 Licença ⚖️

Este projeto está licenciado sob a [MIT License](https://www.google.com/search?q=LICENSE).

-----

## ✉️ Contato 📧

Dúvidas, sugestões ou colaborações, fale comigo pelo [GitHub Issues](https://github.com/seu-usuario/yii2-exemplos-comentados/issues).

-----

Feito com ❤️ para ajudar a comunidade Yii2 brasileira\!

-----