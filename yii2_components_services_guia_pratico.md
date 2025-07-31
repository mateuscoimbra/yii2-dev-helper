# 🧩 Diretórios `components/` e `services/` no Yii2 — Guia Prático

Separar responsabilidades é uma das melhores práticas no desenvolvimento Yii2. Os diretórios `components/` e `services/` não são obrigatórios, mas **fortemente recomendados** para manter o código organizado, reutilizável e testável.

---

## 📦 `components/` — Classes Utilitárias e Reutilizáveis

### ✅ Para que serve?

Armazena classes que:
- Executam tarefas genéricas (helpers)
- São usadas em vários lugares
- Estendem `yii\base\Component` para registro global (`Yii::$app->...`)

### 📄 Exemplo 1: Helper estático

```php
// components/CpfHelper.php
namespace app\components;

class CpfHelper {
    public static function validar($cpf) {
        return preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf);
    }
}
```

Uso:
```php
use app\components\CpfHelper;
if (CpfHelper::validar('123.456.789-09')) { ... }
```

---

### 📄 Exemplo 2: Componente registrado no Yii

```php
// components/MyMailer.php
namespace app\components;

use yii\base\Component;

class MyMailer extends Component {
    public $from;

    public function send($to, $subject, $body) {
        return mail($to, $subject, $body, "From: {$this->from}");
    }
}
```

`config/web.php`:
```php
'components' => [
    'myMailer' => [
        'class' => 'app\components\MyMailer',
        'from' => 'no-reply@meusistema.com',
    ],
],
```

Uso:
```php
Yii::$app->myMailer->send('destinatario@teste.com', 'Assunto', 'Mensagem');
```

---

## 🧭 `services/` — Lógica de Negócio (Service Layer)

### ✅ Para que serve?

Agrupa regras de negócio que **não pertencem a Models ou Controllers**, ajudando a manter:
- Controllers leves
- Lógica centralizada e testável
- Aplicações desacopladas

### 📄 Exemplo: UserService

```php
// services/UserService.php
namespace app\services;

use app\models\User;
use Yii;

class UserService {
    public function register($formData) {
        $user = new User();
        $user->attributes = $formData;
        $user->setPassword($formData['password']);
        $user->generateAuthKey();

        if ($user->save()) {
            Yii::$app->mailer->compose()
                ->setTo($user->email)
                ->setSubject("Bem-vindo!")
                ->setTextBody("Conta criada.")
                ->send();
            return $user;
        }

        return null;
    }
}
```

Uso em Controller:
```php
$service = new \app\services\UserService();
$user = $service->register(Yii::$app->request->post('User'));
```

---

## ✅ Resumo Final

| Diretório     | Para que serve                                                 |
|---------------|----------------------------------------------------------------|
| `components/` | Classes utilitárias, helpers, ou componentes registrados no Yii|
| `services/`   | Lógica de negócio reutilizável entre controllers/models        |

---

## 📚 Leitura complementar

- [Guia Yii2: Estrutura da Aplicação](https://www.yiiframework.com/doc/guide/2.0/en/structure-overview)
- [Componentes personalizados](https://www.yiiframework.com/doc/guide/2.0/en/structure-application-components)
- [Separação de camadas com Service Layer (artigo externo)](https://en.wikipedia.org/wiki/Service_layer_pattern)

---

Com esses padrões, você terá uma aplicação Yii2 **mais organizada, manutenível e escalável**!