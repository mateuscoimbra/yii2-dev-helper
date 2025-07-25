# 🛡️ Yii2 — Guia Prático de Controle de Acesso com RBAC

O RBAC (Role-Based Access Control) no Yii2 é um sistema robusto de **autorização baseada em papéis**, que permite controlar quem pode acessar o quê, de forma flexível e escalável.

---

## ⚙️ 1. Habilitando o RBAC no Yii2

### `config/web.php`

```php
'components' => [
    'authManager' => [
        'class' => 'yii\rbac\DbManager', // Ou PhpManager
    ],
],
```

> ⚠️ Execute a migração padrão do Yii2 para criar as tabelas necessárias:
```bash
php yii migrate --migrationPath=@yii/rbac/migrations/
```

---

## 🧱 2. Estrutura do RBAC

- **Roles** (Papéis) — exemplo: `admin`, `editor`, `cliente`
- **Permissions** (Permissões) — exemplo: `editarPost`, `deletarUsuario`
- **Rules** (Regras customizadas) — lógica condicional adicional

---

## 🛠️ 3. Criando Papéis e Permissões (via comando ou seed)

```php
$auth = Yii::$app->authManager;

// Permissões
$manageUsers = $auth->createPermission('manageUsers');
$manageUsers->description = 'Gerenciar usuários';
$auth->add($manageUsers);

// Papel Admin
$admin = $auth->createRole('admin');
$auth->add($admin);
$auth->addChild($admin, $manageUsers);

// Atribuir papel a usuário
$auth->assign($admin, $userId);
```

---

## 🔐 4. Verificando Permissões

### Em Controller:
```php
if (Yii::$app->user->can('manageUsers')) {
    // ação autorizada
} else {
    throw new \yii\web\ForbiddenHttpException('Acesso negado.');
}
```

### Em View:
```php
if (Yii::$app->user->can('manageUsers')) {
    echo Html::a('Gerenciar', ['usuario/index']);
}
```

---

## 🚫 5. AccessControl Filter (ACF) — Restrição por Controller

```php
use yii\filters\AccessControl;

public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'], // usuários autenticados
                ],
                [
                    'allow' => true,
                    'roles' => ['admin'], // apenas papel "admin"
                ],
            ],
        ],
    ];
}
```

---

## 🧠 6. Criando uma Rule personalizada

```php
use yii\rbac\Rule;

class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    public function execute($user, $item, $params)
    {
        return isset($params['post']) && $params['post']->created_by == $user;
    }
}
```

Registre no `authManager`:

```php
$rule = new AuthorRule();
$auth->add($rule);

$updateOwnPost = $auth->createPermission('updateOwnPost');
$updateOwnPost->description = 'Atualizar o próprio post';
$updateOwnPost->ruleName = $rule->name;
$auth->add($updateOwnPost);
```

---

## 📦 7. Tabelas usadas no RBAC (com DbManager)

| Tabela              | Finalidade                        |
|---------------------|-----------------------------------|
| `auth_item`         | Armazena papéis e permissões      |
| `auth_item_child`   | Define relações entre itens       |
| `auth_assignment`   | Atribui papéis aos usuários       |
| `auth_rule`         | Armazena regras personalizadas    |

---

## 🧪 Testando com Fixture / Seeder

Você pode criar um comando `yii rbac/init` para popular o sistema com roles e permissões iniciais.

---

## 📚 Referências

- [Guia oficial de RBAC](https://www.yiiframework.com/doc/guide/2.0/en/security-authorization)
- [Yii2 Rbac Extension Avançada](https://github.com/dektrium/yii2-rbac)
- [RBAC Admin Module (yiister/yii2-admin)](https://github.com/yiister/yii2-admin)

---

Com o RBAC, você garante segurança granular com controle centralizado de acesso. Ideal para sistemas administrativos e aplicações multiusuário!

---

## 🔒 8. Exemplo Completo de AccessControl e VerbFilter em `behaviors()`

```php
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::className(),
            'only' => ['index', 'view', 'create', 'update', 'delete'],
            'rules' => [
                [
                    'actions' => ['index', 'view', 'create', 'update', 'delete'],
                    'allow' => true,
                    'roles' => ['@'], // Apenas usuários autenticados
                ],
                // Para permitir apenas admins:
                // [
                //     'actions' => ['delete'],
                //     'allow' => true,
                //     'roles' => ['admin'],
                // ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['POST'], // Impede deleção via GET por segurança
            ],
        ],
    ];
}
```

> O `AccessControl` é ideal para proteger actions específicas com base na autenticação e no RBAC. Já o `VerbFilter` impede requisições indesejadas via métodos não permitidos (como GET em exclusões).

---

