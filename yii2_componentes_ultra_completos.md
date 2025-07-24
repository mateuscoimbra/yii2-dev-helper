
# Yii2 — Exemplos Ultra Completos de Componentes (ActiveForm, DetailView, GridView)

Este guia fornece **exemplos altamente comentados e abrangentes** dos principais componentes do Yii2 para desenvolvedores iniciantes. Ele serve como referência rápida e material de apoio para facilitar cópia, adaptação e padronização de código.

---

## 🧾 1. ActiveForm::begin()

```php
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'form-id',
    'method' => 'post',
    'action' => ['site/enviar'],
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'validationUrl' => ['site/validar'],
    'enableClientScript' => true,
    'options' => [
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data',
        'data-formulario' => 'custom',
    ],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n{error}",
        'labelOptions' => ['class' => 'col-lg-4 control-label text-right'],
        'options' => ['class' => 'form-group row'],
    ],
]);
```

### 🔚 Fechamento
```php
<?php ActiveForm::end(); ?>
```

---

## 🧱 2. $form->field() Ultra Completo

```php
echo $form->field(
    $model,
    'email',
    [
        'options' => [
            'class' => 'form-group row has-feedback my-custom-class',
            'id' => 'wrapper-email-field',
            'style' => 'background: #f8f9fc;',
            'data-custom' => 'valor',
        ],
        'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n{hint}\n{error}",
        'labelOptions' => [
            'class' => 'col-sm-2 col-form-label text-right text-primary',
            'id' => 'label-email',
        ],
        'errorOptions' => [
            'class' => 'text-danger small',
            'id' => 'email-error',
        ],
        'hintOptions' => [
            'class' => 'form-text text-muted small',
            'tag' => 'small',
            'id' => 'hint-email',
        ],
    ]
)->textInput([
    'type' => 'email',
    'id' => 'email-input',
    'class' => 'form-control form-control-user',
    'placeholder' => 'Digite seu e-mail',
    'maxlength' => 255,
    'autofocus' => true,
    'autocomplete' => 'email',
])->label('E-mail do Usuário', [
    'class' => 'label-class text-info',
    'for' => 'email-input'
])->hint('Utilize seu e-mail institucional.');
```

---

## 📋 3. DetailView::widget()

```php
use yii\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'table table-bordered table-striped'],
    'attributes' => [
        'id',
        [
            'attribute' => 'email',
            'label' => 'Endereço de E-mail',
            'format' => 'email',
            'value' => fn($model) => strtolower($model->email),
            'contentOptions' => ['class' => 'text-info'],
        ],
        [
            'label' => 'Status',
            'value' => $model->ativo ? 'Ativo' : 'Inativo',
        ],
    ],
]);
```

---

## 📊 4. GridView::widget()

```php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$dataProvider = new ActiveDataProvider([
    'query' => \app\models\Usuario::find(),
    'pagination' => ['pageSize' => 10],
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => "{summary}\n{items}\n{pager}",
    'tableOptions' => ['class' => 'table table-bordered table-striped'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'nome',
        'email:email',
        [
            'attribute' => 'status',
            'filter' => ['1' => 'Ativo', '0' => 'Inativo'],
            'value' => fn($model) => $model->status ? 'Ativo' : 'Inativo',
            'contentOptions' => ['class' => 'text-center'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
        ],
    ],
]);
```

---

## 📌 Notas Finais

- Todos os componentes acima podem ser adaptados com `Html::tag()`, `Html::activeX()` e helpers como `Html::a()`, `Html::submitButton()`.
- Utilize `format => 'raw'` para HTML não escapado no `GridView` e `DetailView`.

---

## ✅ Exemplo Yii2 `$form->field()` ultra comentado

```php
echo $form->field(
    $model,
    'email',
    [
        'options' => [
            'class' => 'form-group row has-feedback my-custom-class',
            'id' => 'wrapper-email-field',
            'style' => 'background: #f8f9fc;',
            'data-custom' => 'valor',
        ],
        'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n{hint}\n{error}",
        'labelOptions' => [
            'class' => 'col-sm-2 col-form-label text-right text-primary',
            'id' => 'label-email',
        ],
        'errorOptions' => [
            'class' => 'text-danger small',
            'id' => 'email-error',
        ],
        'hintOptions' => [
            'class' => 'form-text text-muted small',
            'tag' => 'small',
            'id' => 'hint-email',
        ],
    ]
)->textInput([
    'type' => 'email',
    'id' => 'email-input',
    'class' => 'form-control form-control-user',
    'placeholder' => 'Digite seu e-mail',
    'maxlength' => 255,
    'autofocus' => true,
    'autocomplete' => 'email',
])->label('E-mail do Usuário', [
    'class' => 'label-class text-info',
    'for' => 'email-input'
])->hint('Utilize seu e-mail institucional.');
```

---

## 📦 Outros exemplos para outros tipos de campos

### 🔑 passwordInput()

```php
$form->field($model, 'senha')->passwordInput([
    'class' => 'form-control',
    'placeholder' => 'Digite sua senha',
    'maxlength' => 32,
]);
```

### 📋 dropDownList()

```php
$form->field($model, 'genero')->dropDownList([
    '' => 'Selecione...',
    'M' => 'Masculino',
    'F' => 'Feminino',
], [
    'class' => 'form-control',
    'prompt' => 'Escolha uma opção',
    'id' => 'select-genero',
]);
```

### ☑️ checkbox()

```php
$form->field($model, 'aceite_termos')->checkbox([
    'label' => 'Li e concordo com os termos',
    'labelOptions' => ['class' => 'form-check-label'],
    'class' => 'form-check-input',
    'uncheck' => null,
]);
```

### 🔘 radioList()

```php
$form->field($model, 'tipo_usuario')->radioList([
    'admin' => 'Administrador',
    'user' => 'Usuário Comum',
], [
    'itemOptions' => ['class' => 'form-check-input'],
    'separator' => '<br>',
]);
```

### 📁 fileInput()

```php
$form->field($model, 'arquivo')->fileInput([
    'class' => 'form-control-file',
    'accept' => '.pdf,.doc,.docx',
]);
```
