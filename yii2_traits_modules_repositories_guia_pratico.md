# 📁 Diretórios `traits/`, `modules/` e `repositories/` no Yii2 — Guia Prático

Além de `components/` e `services/`, aplicações Yii2 de médio e grande porte se beneficiam de outras estruturas como `traits/`, `modules/` e `repositories/`. Este guia explica o uso e dá exemplos práticos para cada uma.

---

## 🔁 `traits/` — Reutilização de Métodos

### ✅ Para que serve?

Traits são um recurso do PHP que permite **reutilizar métodos comuns entre múltiplas classes**, sem herança direta.

Use `traits/` quando:
- Vários Models ou Controllers compartilham **lógica repetida**
- Deseja **reduzir duplicidade de código**

### 📄 Exemplo: `traits/TimestampTrait.php`

```php
namespace app\traits;

trait TimestampTrait
{
    public function updateTimestamps()
    {
        $this->updated_at = time();
        if ($this->isNewRecord) {
            $this->created_at = time();
        }
    }
}
```

**Uso:**
```php
use app\traits\TimestampTrait;

class MyModel extends \yii\db\ActiveRecord
{
    use TimestampTrait;

    public function beforeSave($insert)
    {
        $this->updateTimestamps();
        return parent::beforeSave($insert);
    }
}
```

---

## 📦 `modules/` — Divisão de Subaplicações

### ✅ Para que serve?

`modules/` permite dividir sua aplicação em **subaplicações ou contextos separados**, cada uma com seus controllers, models e views.

Ideal para:
- Áreas como `admin`, `api`, `painel-cliente`
- Projetos com múltiplas equipes ou domínios de negócio

### 📄 Exemplo de estrutura de módulo `admin/`

```
modules/
└── admin/
    ├── controllers/
    │   └── DefaultController.php
    ├── models/
    ├── views/
    │   └── default/
    │       └── index.php
    └── Module.php
```

**modules/admin/Module.php**
```php
namespace app\modules\admin;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function init()
    {
        parent::init();
    }
}
```

**Configuração (`web.php`):**
```php
'modules' => [
    'admin' => [
        'class' => 'app\modules\admin\Module',
    ],
],
```

Acesse via: `/index.php?r=admin/default/index`

---

## 🗃 `repositories/` — Camada de Acesso a Dados

### ✅ Para que serve?

O padrão `Repository` isola a **lógica de acesso ao banco de dados** fora do Model e Service.

Use quando:
- Quer testar sua aplicação desacoplada do banco
- Precisa de queries complexas ou reusáveis

### 📄 Exemplo: `repositories/UserRepository.php`

```php
namespace app\repositories;

use app\models\User;

class UserRepository
{
    public function findByEmail($email)
    {
        return User::find()->where(['email' => $email])->one();
    }

    public function allAdmins()
    {
        return User::find()->where(['role' => 'admin'])->all();
    }
}
```

**Uso em Service:**
```php
use app\repositories\UserRepository;

$repo = new UserRepository();
$user = $repo->findByEmail('teste@teste.com');
```

---

## ✅ Conclusão

| Diretório       | Para que serve                                               |
|-----------------|--------------------------------------------------------------|
| `traits/`       | Compartilhar métodos comuns entre múltiplas classes          |
| `modules/`      | Subaplicações com controllers, models e views próprios       |
| `repositories/` | Camada de persistência de dados, isolando lógica de banco    |

---

## 📚 Referências

- [PHP Traits](https://www.php.net/manual/en/language.oop5.traits.php)
- [Yii2 Modules Guide](https://www.yiiframework.com/doc/guide/2.0/en/structure-modules)
- [Repository Pattern](https://martinfowler.com/eaaCatalog/repository.html)

Utilizar essas estruturas auxilia no crescimento saudável e escalável da aplicação.