# Yii2 — Exemplos Ultra Completos de Componentes (ActiveForm, DetailView, GridView) 🚀

Este guia fornece **exemplos altamente comentados e abrangentes** dos principais componentes do Yii2 para desenvolvedores iniciantes. Ele serve como referência rápida e material de apoio para facilitar cópia, adaptação e padronização de código.

---

## 🧾 1. ActiveForm::begin() ✨

O `ActiveForm::begin()` inicia um formulário HTML conectado a um modelo. Ele permite a geração de campos de formulário, validação de cliente, validação AJAX e outras funcionalidades do Yii2.

```php
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'form-id', // ID único do formulário HTML
    'method' => 'post', // Método HTTP para o envio do formulário (POST, GET)
    'action' => ['site/enviar'], // URL para onde o formulário será submetido
    'enableClientValidation' => true, // Habilita a validação JavaScript no navegador do cliente
    'enableAjaxValidation' => false, // Desabilita a validação assíncrona (AJAX) em tempo real
    'validationUrl' => ['site/validar'], // URL para onde as requisições AJAX de validação são enviadas
    'enableClientScript' => true, // Habilita a inclusão de scripts JavaScript necessários pelo ActiveForm
    'options' => [
        'class' => 'form-horizontal', // Classe CSS para estilos Bootstrap (ex: layout horizontal)
        'enctype' => 'multipart/form-data', // Essencial para formulários com upload de arquivos
        'data-formulario' => 'custom', // Atributo de dado HTML personalizado
    ],
    'fieldConfig' => [
        // Configuração padrão aplicada a todos os campos ($form->field()) deste formulário
        'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n{error}", // Define a ordem e estrutura dos elementos de um campo
        'labelOptions' => ['class' => 'col-lg-4 control-label text-right'], // Opções CSS para o label do campo
        'options' => ['class' => 'form-group row'], // Opções CSS para o contêiner (div) do campo
    ],
]);
````

### 🔚 Fechamento do Formulário 🔒

É crucial chamar `ActiveForm::end()` para fechar o formulário e renderizar tags HTML de fechamento e scripts JavaScript necessários.

```php
<?php ActiveForm::end(); ?>
```

-----

## 🧱 2. $form-\>field() Ultra Completo 🚀

O método `$form->field()` cria um objeto `ActiveField` que representa um campo de entrada de formulário para um atributo de modelo específico.

```php
use yii\helpers\Html; // Importar Html helper para labels com HTML

echo $form->field(
    $model, // Instância do modelo (ex: new Usuario())
    'email', // Atributo do modelo para o qual o campo está sendo criado
    [
        'options' => [
            'class' => 'form-group row has-feedback my-custom-class', // Classes CSS para o wrapper do campo (div.form-group)
            'id' => 'wrapper-email-field', // ID HTML para o wrapper do campo
            'style' => 'background: #f8f9fc;', // Estilos CSS inline para o wrapper
            'data-custom' => 'valor', // Atributo de dado HTML personalizado para o wrapper
        ],
        // O 'template' abaixo sobrescreve o 'fieldConfig' global do ActiveForm para este campo específico
        'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n{hint}\n{error}", // Define a ordem e estrutura dos elementos (label, input, hint, error)
        'labelOptions' => [
            'class' => 'col-sm-2 col-form-label text-right text-primary', // Classes CSS para o label
            'id' => 'label-email', // ID HTML para o label
        ],
        'errorOptions' => [
            'class' => 'text-danger small', // Classes CSS para a mensagem de erro
            'id' => 'email-error', // ID HTML para a mensagem de erro
        ],
        'hintOptions' => [
            'class' => 'form-text text-muted small', // Classes CSS para a dica (hint)
            'tag' => 'small', // Tag HTML para a dica
            'id' => 'hint-email', // ID HTML para a dica
        ],
    ]
)->textInput([ // Tipo de input HTML: textInput (para texto, email, number etc.)
    'type' => 'email', // Define o tipo HTML5 do input (ex: 'email', 'number', 'date')
    'id' => 'email-input', // ID HTML para o elemento <input> real
    'class' => 'form-control form-control-user', // Classes CSS para o <input>
    'placeholder' => 'Digite seu e-mail', // Texto de placeholder no input
    'maxlength' => 255, // Número máximo de caracteres permitidos
    'autofocus' => true, // O campo receberá foco automaticamente ao carregar a página
    'autocomplete' => 'email', // Sugestão de preenchimento automático do navegador
])->label('E-mail do Usuário <i class="fas fa-envelope"></i>', [ // Label personalizado para o campo, com ícone
    'class' => 'label-class text-info', // Classes CSS para o label customizado
    'for' => 'email-input', // Associa o label ao input pelo ID
    'encode' => false, // Desabilita o escape de HTML no label, permitindo tags como <i>
])->hint('Utilize seu e-mail institucional.'); // Dica (hint) exibida abaixo do campo
```

-----

## 📋 3. DetailView::widget() 📊

O `DetailView` é usado para exibir os detalhes de um único registro do modelo. É ideal para páginas de "ver detalhes" de um item específico.

```php
use yii\widgets\DetailView;
use yii\helpers\Html; // Para usar Html::img, Html::encode etc.

echo DetailView::widget([
    'model' => $model, // O modelo de dados cujos detalhes serão exibidos
    'options' => ['class' => 'table table-bordered table-striped detail-view'], // Classes CSS para a tabela HTML
    'template' => '<tr><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>', // Template para cada linha (label e valor)
    'attributes' => [
        'id', // Exibe o atributo 'id' do modelo diretamente
        [
            'attribute' => 'email', // O nome do atributo no modelo
            'label' => 'Endereço de E-mail', // Label personalizado para exibição
            'format' => 'email', // Formata o valor como um link de e-mail (mailto:)
            'value' => fn($model) => strtolower($model->email), // Função de callback para formatar o valor antes de exibir
            'contentOptions' => ['class' => 'text-info'], // Classes CSS para a célula que contém o valor
            'captionOptions' => ['class' => 'bg-light text-primary'], // Classes CSS para a célula que contém o label
        ],
        [
            'label' => 'Status', // Label personalizado (sem atributo específico do modelo)
            'value' => $model->ativo ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>',
            'format' => 'raw', // Essencial para renderizar HTML (ex: tags <span> para badges)
        ],
        [
            'attribute' => 'data_criacao',
            'label' => 'Data de Criação',
            'format' => ['datetime', 'php:d/m/Y H:i:s'], // Formato de data e hora personalizado usando sintaxe PHP
            'contentOptions' => ['class' => 'text-muted'],
        ],
        [
            'label' => 'Informações Adicionais',
            // Combina múltiplos campos ou formata com HTML
            'value' => Html::tag('strong', 'Nome: ') . $model->nome . '<br>' . Html::tag('em', 'ID: ') . $model->id,
            'format' => 'raw', // Para renderizar o HTML customizado
            'captionOptions' => ['class' => 'bg-light'],
        ],
        [
            'label' => 'Foto de Perfil',
            // Exibe uma imagem se a URL existir, caso contrário 'N/A'
            'value' => !empty($model->foto_url) ? Html::img($model->foto_url, ['width' => '100px', 'class' => 'img-thumbnail']) : 'N/A',
            'format' => 'raw', // Para renderizar a tag <img>
        ],
    ],
]);
```

-----

## 📊 4. GridView::widget() 📈

O `GridView` é um dos widgets mais flexíveis e úteis do Yii2 para exibir dados em formato de tabela. Ele suporta paginação, ordenação e filtragem.

```php
use yii\grid\GridView;
use yii\data\ActiveDataProvider; // Para prover dados de um Active Record
use yii\helpers\Html; // Para helpers HTML (botões, links, imagens)
use yii\helpers\Url; // Para gerar URLs programaticamente

$dataProvider = new ActiveDataProvider([
    'query' => \app\models\Usuario::find(), // Sua query de Active Record para buscar os dados
    'pagination' => [
        'pageSize' => 10, // Número de registros por página
        'pageParam' => 'p', // Nome do parâmetro de página na URL (ex: ?p=2)
    ],
    'sort' => [
        'defaultOrder' => ['id' => SORT_DESC], // Ordenação padrão ao carregar
        'attributes' => [ // Atributos pelos quais a ordenação é permitida
            'id',
            'nome',
            'email',
            'status',
            'data_registro',
        ],
    ],
]);

echo GridView::widget([
    'dataProvider' => $dataProvider, // O provedor de dados (ActiveDataProvider, ArrayDataProvider etc.)
    'filterModel' => $searchModel, // O modelo de busca usado para os filtros nas colunas
    'layout' => "{summary}\n{items}\n{pager}", // Ordem dos elementos: resumo, tabela, paginação
    'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'], // Classes CSS para a tabela <table>
    'summaryOptions' => ['class' => 'alert alert-info text-right p-2'], // Opções para a div do resumo de registros
    'pager' => [ // Configurações para o widget de paginação
        'options' => ['class' => 'pagination justify-content-center mt-3'], // Centraliza a paginação Bootstrap
        'prevPageLabel' => '<i class="fas fa-chevron-left"></i> Anterior', // Texto do botão "Anterior"
        'nextPageLabel' => 'Próximo <i class="fas fa-chevron-right"></i>', // Texto do botão "Próximo"
        'maxButtonCount' => 7, // Número máximo de botões de página visíveis
        'linkContainerOptions' => ['class' => 'page-item'], // Classe para o <li> que envolve o link
        'linkOptions' => ['class' => 'page-link'], // Classe para o <a> do link
        'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link disabled'], // Estilo para links de página desabilitados
    ],
    'columns' => [ // Definição das colunas da tabela
        ['class' => 'yii\grid\SerialColumn'], // Coluna que exibe um número de série incremental
        
        // Coluna simples: atributo:tipo_de_formato:label_do_cabeçalho
        'id:integer:ID do Usuário', 
        'nome:text',
        'email:email', // Formatação automática de e-mail como link mailto:

        // Coluna personalizada para o status do usuário
        [
            'attribute' => 'status', // Atributo do modelo
            'label' => 'Situação', // Rótulo exibido no cabeçalho da coluna
            'filter' => ['1' => 'Ativo', '0' => 'Inativo'], // Filtro dropDownList para esta coluna
            'value' => fn($model) => $model->status ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>',
            'format' => 'raw', // Essencial para renderizar HTML (badges Bootstrap)
            'contentOptions' => ['class' => 'text-center align-middle'], // Opções CSS para o conteúdo da célula
            'headerOptions' => ['class' => 'bg-primary text-white text-center'], // Opções CSS para o cabeçalho da coluna
            'filterInputOptions' => ['prompt' => 'Todos os Status', 'class' => 'form-control form-control-sm'], // Opções para o campo de filtro
        ],
        // Coluna de data com formatação específica
        [
            'attribute' => 'data_registro',
            'label' => 'Data de Registro',
            'format' => ['date', 'php:d/m/Y H:i'], // Formato de data e hora usando PHP strftime
            'contentOptions' => ['class' => 'text-nowrap'], // Evita quebra de linha para datas/horas longas
            'filter' => false, // Remove o filtro para esta coluna específica
            'headerOptions' => ['class' => 'text-center'],
        ],
        // Coluna de imagem (exemplo)
        [
            'label' => 'Miniatura',
            'format' => 'raw', // Permite renderizar HTML
            'value' => fn($model) => !empty($model->thumbnail_url) ? Html::img($model->thumbnail_url, ['alt' => 'Miniatura', 'style' => 'width:50px; height:auto; border-radius:3px;']) : 'Sem Imagem',
            'contentOptions' => ['class' => 'text-center'],
            'filter' => false, // Sem filtro para imagens
            'enableSorting' => false, // Desabilita ordenação para esta coluna
        ],
        // Coluna de Ações Rápidas Personalizadas
        [
            'label' => 'Ações Rápidas',
            'format' => 'raw', // Permite renderizar botões HTML
            'value' => fn($model) => Html::a('Ver Detalhes', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-info me-1']) . ' ' .
                                 Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']),
            'contentOptions' => ['class' => 'text-center text-nowrap'], // Evita quebra de linha dos botões
            'filter' => false, // Sem filtro
            'enableSorting' => false, // Sem ordenação
        ],
        // ActionColumn: coluna padrão com botões de ação (view, update, delete)
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete} {custom}', // Define quais botões serão exibidos e um botão customizado
            'header' => 'Opções', // Rótulo do cabeçalho da coluna
            'headerOptions' => ['class' => 'text-center bg-secondary text-white'],
            'contentOptions' => ['class' => 'text-center text-nowrap'], // Evita quebra de linha dos botões
            'buttons' => [ // Define botões customizados
                'custom' => fn($url, $model, $key) => Html::a('<span class="fas fa-power-off"></span>', Url::to(['usuario/alternar-status', 'id' => $model->id]), [
                    'title' => $model->status ? 'Desativar Usuário' : 'Ativar Usuário', // Título do tooltip
                    'data-pjax' => '0', // Impede PJAX para este link
                    'data-confirm' => 'Tem certeza que deseja alterar o status deste usuário?', // Confirmação JavaScript
                    'class' => 'btn btn-outline-secondary btn-sm ms-1',
                    'data-method' => 'post', // Força o método POST para a ação
                ]),
            ],
            // 'urlCreator' => fn($action, $model, $key, $index) => Url::to([$action, 'id' => $key]), // Função para criar URLs (padrão se não definida)
        ],
    ],
]);
```

-----

## 📌 Notas Finais 📝

  - Todos os componentes acima podem ser adaptados com `Html::tag()`, `Html::activeX()` e helpers como `Html::a()`, `Html::submitButton()` para uma construção de HTML mais dinâmica e segura.
  - Utilize `format => 'raw'` para renderizar HTML não escapado no `GridView` e `DetailView`. Isso é **essencial** quando você insere tags HTML diretamente nos valores das colunas ou atributos.

-----

## 📦 Outros Exemplos de Tipos de Campos para `ActiveForm` 🧩

### 🔑 `passwordInput()`

```php
$form->field($model, 'senha')->passwordInput([
    'class' => 'form-control',
    'placeholder' => 'Digite sua senha',
    'maxlength' => 32,
    'autocomplete' => 'new-password', // Sugere ao navegador não autocompletar com senhas salvas
]);
```

### 📋 `dropDownList()`

```php
$form->field($model, 'genero')->dropDownList([
    '' => 'Selecione...', // Opção padrão vazia
    'M' => 'Masculino',
    'F' => 'Feminino',
], [
    'class' => 'form-control',
    'prompt' => 'Escolha uma opção', // Texto da primeira opção
    'id' => 'select-genero',
    'options' => [
        'F' => ['disabled' => true], // Exemplo de opção desabilitada
    ],
]);
```

### ☑️ `checkbox()`

```php
use yii\helpers\Html; // Para usar Html::checkbox

$form->field($model, 'aceite_termos')->checkbox([
    'label' => 'Li e concordo com os termos',
    'labelOptions' => ['class' => 'form-check-label'],
    'class' => 'form-check-input',
    'uncheck' => null, // Garante que o campo não será enviado se desmarcado
    'value' => 1, // Valor a ser enviado quando marcado
    'checked' => (bool)$model->aceite_termos, // Define o estado inicial do checkbox
]);
```

### 🔘 `radioList()`

```php
use yii\helpers\Html; // Para usar Html::radio

$form->field($model, 'tipo_usuario')->radioList([
    'admin' => 'Administrador',
    'user' => 'Usuário Comum',
], [
    'item' => fn($index, $label, $name, $checked, $value) =>
        '<div class="form-check">' . // Wrapper para cada item
            Html::radio($name, $checked, [
                'value' => $value,
                'class' => 'form-check-input',
                'id' => 'radio-' . $value,
            ]) .
            Html::label($label, 'radio-' . $value, ['class' => 'form-check-label']) .
        '</div>',
    'separator' => '', // Remove o separador padrão se usar 'item'
    'encode' => false, // Permite HTML no label do item
]);
```

### 📁 `fileInput()`

```php
$form->field($model, 'arquivo')->fileInput([
    'class' => 'form-control-file',
    'accept' => '.pdf,.doc,.docx,image/*', // Aceita múltiplos tipos de arquivo
    'multiple' => true, // Permite seleção de múltiplos arquivos
]);
```

### 📝 `textarea()`

```php
$form->field($model, 'observacoes')->textarea([
    'rows' => 6, // Número de linhas visíveis
    'placeholder' => 'Insira suas observações aqui...',
    'class' => 'form-control',
    'style' => 'resize: vertical;', // Permite redimensionar apenas verticalmente
    'maxlength' => 1000,
]);
```

### 👁️ `hiddenInput()`

```php
// Para um campo oculto gerenciado pelo ActiveForm
echo $form->field($model, 'id_usuario_oculto')->hiddenInput(['value' => Yii::$app->user->id])->label(false);

// Ou diretamente com o Html helper para um campo oculto simples
use yii\helpers\Html;
echo Html::hiddenInput('token_seguranca', Yii::$app->security->generateRandomString());
```

### 🔢 `numberInput()`

```php
$form->field($model, 'quantidade')->textInput([
    'type' => 'number', // Tipo HTML5 para números
    'min' => 1, // Valor mínimo permitido
    'max' => 100, // Valor máximo permitido
    'step' => 1, // Incremento/decremento do valor
    'placeholder' => 'Quantidade',
    'class' => 'form-control',
]);
```

### 📅 `dateInput()` (HTML5)

```php
$form->field($model, 'data_nascimento')->textInput([
    'type' => 'date', // Tipo HTML5 para seleção de data
    'class' => 'form-control',
    'min' => '1900-01-01', // Data mínima
    'max' => date('Y-m-d'), // Data máxima (hoje)
]);
```
-----