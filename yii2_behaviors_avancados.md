
# 🎯 Yii2 — Behaviors Avançados (Reutilizáveis, Automatizados e Poderosos)

Este guia cobre o uso de **Behaviors no Yii2**, uma funcionalidade poderosa para reutilizar lógica comum em modelos, componentes e controllers. Ideal para auditoria, automação de timestamps, UUIDs, usuários logados, entre outros.

---

## 🔄 O que é um Behavior?

Um **Behavior** é uma classe que pode ser "anexada" a outro objeto para adicionar novos métodos ou reagir a eventos do Yii2, como `beforeInsert`, `afterSave`, etc.

---

## ✅ Exemplo 1 — `TimestampBehavior`

Atualiza automaticamente os campos `created_at` e `updated_at`.

```php
use yii\behaviors\TimestampBehavior;

public function behaviors()
{
    return [
        TimestampBehavior::class,
    ];
}
```

> Requisitos: campos `created_at` e `updated_at` (normalmente `INT` ou `DATETIME`) devem existir no banco de dados.

---

## 👤 Exemplo 2 — `BlameableBehavior`

Preenche automaticamente `created_by` e `updated_by` com o ID do usuário logado.

```php
use yii\behaviors\BlameableBehavior;

public function behaviors()
{
    return [
        BlameableBehavior::class,
    ];
}
```

> Requisitos: campos `created_by` e `updated_by` no banco de dados e sistema de login habilitado.

---

## 🆔 Exemplo 3 — `UuidBehavior` Personalizado

Gera um UUID automaticamente no `beforeInsert`.

### Arquivo: `app/behaviors/UuidBehavior.php`

```php
namespace app\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;

class UuidBehavior extends Behavior
{
    public $attribute = 'uuid';

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'generateUuid',
        ];
    }

    public function generateUuid($event)
    {
        if (empty($this->owner->{$this->attribute})) {
            $this->owner->{$this->attribute} = StringHelper::uuid();
        }
    }
}
```

### Uso no modelo:

```php
use app\behaviors\UuidBehavior;

public function behaviors()
{
    return [
        'uuid' => ['class' => UuidBehavior::class],
    ];
}
```

---

## 🔄 Criando Behaviors Reutilizáveis

Você pode criar behaviors para qualquer lógica que se repita nos modelos:

- Log de alterações
- Normalização de strings (ex: CPF formatado)
- Geração de slug
- Conversão automática de datas
- Trigger de envio de e-mail no `afterInsert`

---

## 🧪 Dica: Teste seu behavior com eventos como:

| Evento                  | Quando ocorre                           |
|------------------------|----------------------------------------|
| `beforeValidate`       | Antes da validação                     |
| `afterValidate`        | Após a validação                       |
| `beforeInsert`         | Antes de inserir no banco              |
| `afterInsert`          | Após inserir no banco                  |
| `beforeUpdate`         | Antes de atualizar no banco            |
| `afterUpdate`          | Após atualizar no banco                |
| `afterFind`            | Após encontrar um registro             |

---

## 🛠️ Referência oficial

- [https://www.yiiframework.com/doc/guide/2.0/en/concept-behaviors](https://www.yiiframework.com/doc/guide/2.0/en/concept-behaviors)

---

Seja criativo: **behaviors bem implementados reduzem drasticamente a repetição de código** e melhoram a organização do projeto.
