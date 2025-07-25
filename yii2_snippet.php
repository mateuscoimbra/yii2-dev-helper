<?php
/**
 * Yii2 - Snippet Base para ActiveForm, GridView e DetailView
 * Este exemplo serve como referência ultra completa e comentada para uso em projetos Yii2.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\MaskedInput;
use yii\data\ActiveDataProvider;

// Mock de um modelo para fins de exemplo. Em um projeto real, você usaria seus próprios modelos.
// Ex: new \app\models\Usuario();
// Aconselha-se usar um model real para testar.
class ExemploModel extends \yii\base\Model
{
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $genero;
    public $aceite_termos;
    public $tipo_usuario;
    public $foto_perfil;
    public $descricao;
    public $id_referencia;
    public $ativo;
    public $data_criacao;
    public $thumbnail_url;
    public $telefone;
    public $celular;
    public $arquivo; // Para fileInput
    public $observacoes; // Para textarea
    public $id_usuario_oculto; // Para hiddenInput
    public $quantidade; // Para numberInput
    public $data_nascimento; // Para dateInput (HTML5)
    public $data_registro; // Para GridView e DetailView

    public function rules()
    {
        return [
            [['nome', 'email', 'senha', 'genero', 'tipo_usuario', 'descricao'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['aceite_termos', 'ativo'], 'boolean'],
            [['id', 'id_referencia', 'quantidade'], 'integer'],
            [['foto_perfil', 'arquivo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, pdf, doc, docx'],
            [['data_criacao', 'data_nascimento', 'data_registro'], 'safe'],
            [['observacoes', 'telefone', 'celular', 'thumbnail_url'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome Completo',
            'email' => 'Endereço de E-mail',
            'senha' => 'Senha',
            'genero' => 'Gênero',
            'aceite_termos' => 'Aceito os Termos',
            'tipo_usuario' => 'Tipo de Usuário',
            'foto_perfil' => 'Foto de Perfil',
            'descricao' => 'Descrição do Usuário',
            'id_referencia' => 'ID de Referência',
            'ativo' => 'Status de Atividade',
            'data_criacao' => 'Data de Criação',
            'thumbnail_url' => 'Miniatura',
            'telefone' => 'Telefone',
            'celular' => 'Celular',
            'arquivo' => 'Anexar Arquivo',
            'observacoes' => 'Observações',
            'id_usuario_oculto' => 'ID do Usuário Oculto',
            'quantidade' => 'Quantidade de Itens',
            'data_nascimento' => 'Data de Nascimento',
            'data_registro' => 'Data de Registro',
        ];
    }
}

// Inicializa o modelo de exemplo
$model = new ExemploModel();
$model->id = 1;
$model->nome = 'João da Silva';
$model->email = 'joao.silva@example.com';
$model->genero = 'M';
$model->aceite_termos = true;
$model->tipo_usuario = 'user';
$model->descricao = 'Este é um usuário de exemplo para demonstração.';
$model->id_referencia = 12345;
$model->ativo = true;
$model->data_criacao = date('Y-m-d H:i:s'); // Data atual para exemplo
$model->data_registro = '2023-01-15 10:30:00';
$model->thumbnail_url = 'https://via.placeholder.com/50x50.png?text=JS'; // URL de exemplo para imagem
$model->telefone = '(XX) XXXX-XXXX';
$model->celular = '(XX) 9XXXX-XXXX';
$model->quantidade = 5;
$model->data_nascimento = '1990-05-20';
$model->observacoes = 'Algumas observações sobre o usuário.';

// Para o GridView, você precisaria de um SearchModel e um ActiveDataProvider real
// Mock de SearchModel para compilação (em um projeto real, seria um \yii\base\Model ou \yii\db\ActiveRecord)
class ExemploSearchModel extends \yii\base\Model
{
    public $id;
    public $nome;
    public $email;
    public $status; // 'status' para o filtro, que mapeia para 'ativo' no modelo
    public $data_registro;

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['nome', 'email', 'data_registro'], 'safe'],
        ];
    }
    // Métodos como scenarios() e search() seriam necessários em um SearchModel real
}
$searchModel = new ExemploSearchModel();
// O search() método seria chamado aqui para aplicar filtros na query
// $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

?>

<?php
$form = ActiveForm::begin([
    'id' => 'form-exemplo',
    'method' => 'post',
    'action' => ['site/enviar'], // Defina sua action real
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'validationUrl' => ['site/validar'], // Defina sua validationUrl real
    'enableClientScript' => true,
    'options' => [
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data', // Essencial para upload de arquivos
        'data-pjax' => '#grid-pjax', // Exemplo de integração com PJAX
    ],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n{error}\n{hint}",
        'labelOptions' => ['class' => 'col-lg-4 control-label text-right'],
        'options' => ['class' => 'form-group row mb-3'],
        'errorOptions' => ['class' => 'invalid-feedback d-block'],
        'hintOptions' => ['class' => 'form-text text-muted'],
    ],
]);
?>

    <h2 class="mb-4"><i class="fas fa-edit"></i> Formulário de Exemplo Completo</h2>

    <?= $form->field($model, 'email', [
        'options' => [
            'class' => 'form-group row has-feedback my-custom-class',
            'id' => 'wrapper-email-field',
            'style' => 'background: #f8f9fc; border-left: 5px solid #007bff; padding: 10px; border-radius: 5px;',
            'data-custom' => 'valor',
        ],
        'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n{hint}\n{error}",
        'labelOptions' => [
            'class' => 'col-sm-2 col-form-label text-right text-primary',
            'id' => 'label-email',
            'data-tooltip' => 'Este é o seu email de login.',
        ],
        'errorOptions' => [
            'class' => 'text-danger small mt-1 ml-auto',
            'id' => 'email-error',
        ],
        'hintOptions' => [
            'class' => 'form-text text-muted small mt-1 ml-auto',
            'tag' => 'small',
            'id' => 'hint-email',
        ],
    ])->textInput([
        'type' => 'email',
        'id' => 'email-input',
        'class' => 'form-control form-control-user',
        'placeholder' => 'Digite seu e-mail profissional',
        'maxlength' => 255,
        'autofocus' => true,
        'autocomplete' => 'email',
        'aria-describedby' => 'hint-email',
    ])->label('E-mail do Usuário <i class="fas fa-envelope"></i>', [
        'class' => 'label-class text-info',
        'for' => 'email-input',
        'encode' => false,
    ])->hint('Utilize seu e-mail institucional para acesso ao sistema.');
    ?>

    <?= $form->field($model, 'senha')->passwordInput([
        'class' => 'form-control',
        'placeholder' => 'Digite sua senha',
        'maxlength' => 32,
        'autocomplete' => 'new-password',
    ])->label('Senha <i class="fas fa-key"></i>') ?>

    <?= $form->field($model, 'genero')->dropDownList(
        [
            '' => 'Selecione...',
            'M' => 'Masculino',
            'F' => 'Feminino',
            'O' => 'Outro',
        ],
        [
            'class' => 'form-control',
            'prompt' => 'Escolha uma opção',
            'id' => 'select-genero',
            'options' => [
                'F' => ['disabled' => true, 'label' => 'Feminino (temporariamente indisponível)'],
            ],
        ]
    )->label('Gênero <i class="fas fa-venus-mars"></i>') ?>

    <?= $form->field($model, 'aceite_termos')->checkbox([
        'label' => 'Li e concordo com os <a href="#" target="_blank">termos de uso</a> e a <a href="#" target="_blank">política de privacidade</a>.',
        'labelOptions' => ['class' => 'form-check-label text-success ml-2'],
        'class' => 'form-check-input',
        'uncheck' => null,
        'value' => 1,
        'checked' => (bool)$model->aceite_termos,
    ], false // Segundo parâmetro 'false' para usar o label das opções
    )->label('<i class="fas fa-check-circle"></i> Aceito os Termos') ?>

    <?= $form->field($model, 'tipo_usuario')->radioList(
        [
            'admin' => 'Administrador',
            'user' => 'Usuário Comum',
            'guest' => 'Visitante',
        ],
        [
            'separator' => '',
            'encode' => false,
            'item' => fn($index, $label, $name, $checked, $value) =>
                '<div class="form-check form-check-inline">' .
                    Html::radio($name, $checked, [
                        'value' => $value,
                        'class' => 'form-check-input',
                        'id' => 'radio-tipo-' . $value,
                    ]) .
                    Html::label($label, 'radio-tipo-' . $value, ['class' => 'form-check-label']) .
                '</div>',
        ]
    )->label('Tipo de Usuário <i class="fas fa-user-tag"></i>') ?>

    <?= $form->field($model, 'foto_perfil')->fileInput([
        'class' => 'form-control-file border p-2 rounded',
        'accept' => 'image/*',
        'onchange' => 'console.log("Arquivo selecionado: " + this.files[0].name);',
    ])->label('Carregar Foto de Perfil <i class="fas fa-image"></i>') ?>

    <?= $form->field($model, 'descricao')->textarea([
        'rows' => 5,
        'placeholder' => 'Descreva brevemente o usuário...',
        'class' => 'form-control',
        'style' => 'resize: vertical;',
        'maxlength' => 500,
    ])->label('Descrição <i class="fas fa-align-left"></i>') ?>

    <?= $form->field($model, 'id_usuario_oculto')->hiddenInput(['value' => $model->id])->label(false) ?>
    <?= Html::hiddenInput('csrf_token_custom', Yii::$app->request->csrfToken); ?>

    <?= $form->field($model, 'quantidade')->textInput([
        'type' => 'number',
        'min' => 1,
        'max' => 100,
        'step' => 1,
        'placeholder' => 'Quantidade',
        'class' => 'form-control',
    ])->label('Quantidade <i class="fas fa-sort-numeric-up-alt"></i>') ?>

    <?= $form->field($model, 'data_nascimento')->textInput([
        'type' => 'date',
        'class' => 'form-control',
        'min' => '1900-01-01',
        'max' => date('Y-m-d'),
    ])->label('Data de Nascimento <i class="fas fa-calendar-alt"></i>') ?>

    <?= $form->field($model, 'data_nascimento')->widget(MaskedInput::class, [
        'mask' => '99/99/9999',
        'clientOptions' => ['alias' => 'datetime', 'placeholder' => 'dd/mm/aaaa']
    ]) ?>

    <?= $form->field($model, 'arquivo')->fileInput(['multiple' => true, 'accept' => '.pdf,image/*']) ?>

    <div class="form-group text-center mt-4">
        <?= Html::submitButton('<i class="fas fa-save"></i> Salvar Dados', [
            'class' => 'btn btn-success btn-lg me-2',
            'name' => 'submit-button',
            'data-confirm' => 'Confirmar o envio dos dados?',
        ]) ?>
        <?= Html::resetButton('<i class="fas fa-redo"></i> Limpar Formulário', [
            'class' => 'btn btn-outline-secondary btn-lg',
        ]) ?>
        <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['site/index'], [
            'class' => 'btn btn-info btn-lg ms-2',
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>

<hr class="my-5">

<h3 class="mb-4"><i class="fas fa-info-circle"></i> Detalhes do Registro</h3>
<?= DetailView::widget([
    'model' => $model,
    'options' => [
        'class' => 'table table-bordered table-striped detail-view',
        'id' => 'user-detail-view',
    ],
    'template' => '<tr><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
    'attributes' => [
        'id',
        [
            'attribute' => 'email',
            'label' => 'Endereço de E-mail',
            'format' => 'email',
            'value' => fn($model) => strtolower($model->email),
            'contentOptions' => ['class' => 'text-info font-weight-bold'],
            'captionOptions' => ['class' => 'bg-light text-primary'],
        ],
        [
            'label' => 'Status do Usuário',
            'value' => $model->ativo ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>',
            'format' => 'raw',
            'contentOptions' => ['class' => 'text-center'],
        ],
        [
            'attribute' => 'data_registro',
            'label' => 'Registrado Em',
            'format' => ['datetime', 'php:d/m/Y H:i:s'],
            'contentOptions' => ['class' => 'text-muted fst-italic'],
        ],
        [
            'label' => 'Data de Nascimento',
            'attribute' => 'data_nascimento',
            'format' => ['date', 'php:d/m/Y'],
            'value' => !empty($model->data_nascimento) ? $model->data_nascimento : 'Não informada',
        ],
        [
            'label' => 'Foto de Perfil',
            'value' => !empty($model->thumbnail_url) ? Html::img($model->thumbnail_url, ['alt' => 'Foto do usuário', 'style' => 'width:150px; border-radius: 8px;']) : 'N/A',
            'format' => 'raw',
        ],
        [
            'label' => 'Dados de Contato',
            'value' => 'Telefone: ' . Html::encode($model->telefone) . '<br>Celular: ' . Html::encode($model->celular),
            'format' => 'raw',
        ],
        [
            'label' => 'Observações',
            'attribute' => 'observacoes',
            'format' => 'ntext', // Formata quebras de linha como <br>
            'contentOptions' => ['style' => 'white-space: pre-wrap;'], // Mantém a formatação do texto
        ],
        [
            'attribute' => 'foto',
            'value' => 'data:image/png;base64,' . $model->foto,
            'format' => ['image', ['width' => 100, 'height' => 100]]
        ],
        'quantidade:integer', // Exibindo o campo numérico
    ],
]) ?>

<hr class="my-5">

<h3 class="mb-4"><i class="fas fa-table"></i> Lista de Usuários</h3>
<?php
// Mock de dados para o DataProvider, em um projeto real, viria do $searchModel->search()
$mockUsers = [];
for ($i = 1; $i <= 20; $i++) {
    $user = new ExemploModel();
    $user->id = $i;
    $user->nome = 'Usuário ' . $i;
    $user->email = 'usuario' . $i . '@example.com';
    $user->status = ($i % 2 === 0); // Alterna entre ativo/inativo
    $user->data_registro = date('Y-m-d H:i:s', strtotime("-$i days"));
    $user->thumbnail_url = "https://via.placeholder.com/50x50.png?text=U$i";
    $mockUsers[] = $user;
}

$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $mockUsers, // Usando ArrayDataProvider para o mock
    'pagination' => [
        'pageSize' => 10,
        'pageParam' => 'pagina',
    ],
    'sort' => [
        'defaultOrder' => ['id' => SORT_ASC],
        'attributes' => [
            'id',
            'nome',
            'email',
            'status',
            'data_registro',
        ],
    ],
]);
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel, // Use o searchModel real aqui
    'layout' => "{summary}\n{items}\n{pager}",
    'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'],
    'summaryOptions' => ['class' => 'alert alert-info text-right p-2'],
    'pager' => [
        'options' => ['class' => 'pagination justify-content-center mt-3'],
        'prevPageLabel' => '<i class="fas fa-chevron-left"></i> Anterior',
        'nextPageLabel' => 'Próximo <i class="fas fa-chevron-right"></i>',
        'maxButtonCount' => 7,
        'linkContainerOptions' => ['class' => 'page-item'],
        'linkOptions' => ['class' => 'page-link'],
        'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link disabled'],
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id:integer:ID',
        'nome:text',
        'email:email',
        [
            'attribute' => 'status',
            'label' => 'Situação',
            'filter' => ['1' => 'Ativo', '0' => 'Inativo'],
            'value' => fn($model) => $model->status ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>',
            'format' => 'raw',
            'contentOptions' => ['class' => 'text-center align-middle'],
            'headerOptions' => ['class' => 'bg-dark text-white text-center'],
            'filterInputOptions' => ['prompt' => 'Todos os Status', 'class' => 'form-control form-control-sm'],
        ],
        [
            'attribute' => 'data_registro',
            'label' => 'Criado Em',
            'format' => ['date', 'php:d/m/Y H:i'],
            'contentOptions' => ['class' => 'text-nowrap'],
            'filter' => false,
            'headerOptions' => ['class' => 'text-center'],
        ],
        [
            'label' => 'Miniatura',
            'format' => 'raw',
            'value' => fn($model) => !empty($model->thumbnail_url) ? Html::img($model->thumbnail_url, ['alt' => 'Miniatura', 'style' => 'width:50px; height:auto; border-radius:3px;']) : 'Sem Imagem',
            'contentOptions' => ['class' => 'text-center'],
            'filter' => false,
            'enableSorting' => false,
        ],
        [
            'attribute' => 'imagem_base64',
            'format' => 'raw',
            'value' => fn($model) => "<img src='data:image/jpeg;base64," . $model->imagem_base64 . "' width='70' height='70'/>",
            'filter' => false,
        ],
        [
            'attribute' => 'campo_admin',
            'visible' => Yii::$app->user->identity->isAdmin,
            'value' => fn($model) => $model->campo_admin
        ],
        [
            'label' => 'Ações Rápidas',
            'format' => 'raw',
            'value' => fn($model) => Html::a('Ver', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-info me-1']) . ' ' .
                                 Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']),
            'contentOptions' => ['class' => 'text-center text-nowrap'],
            'filter' => false,
            'enableSorting' => false,
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete} {ativar}',
            'header' => 'Opções',
            'headerOptions' => ['class' => 'text-center bg-secondary text-white'],
            'contentOptions' => ['class' => 'text-center text-nowrap'],
            'buttons' => [
                'view' => fn($url, $model, $key) => Html::a('<span class="fas fa-eye"></span>', $url, [
                    'title' => 'Visualizar Detalhes',
                    'class' => 'btn btn-success btn-sm me-1',
                    'data-pjax' => '0',
                ]),
                'update' => fn($url, $model, $key) => Html::a('<span class="fas fa-edit"></span>', $url, [
                    'title' => 'Editar Registro',
                    'class' => 'btn btn-primary btn-sm me-1',
                    'data-pjax' => '0',
                ]),
                'delete' => fn($url, $model, $key) => Html::a('<span class="fas fa-trash-alt"></span>', $url, [
                    'title' => 'Excluir Registro',
                    'class' => 'btn btn-danger btn-sm me-1',
                    'data-confirm' => 'Tem certeza que deseja excluir este item permanentemente?',
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]),
                'ativar' => fn($url, $model, $key) => Html::a('<span class="fas fa-power-off"></span>', Url::to(['usuario/alternar-status', 'id' => $model->id]), [
                    'title' => $model->status ? 'Desativar Usuário' : 'Ativar Usuário',
                    'class' => 'btn btn-outline-secondary btn-sm',
                    'data-pjax' => '0',
                    'data-confirm' => $model->status ? 'Desativar este usuário?' : 'Ativar este usuário?',
                    'data-method' => 'post',
                ]),
            ],
            'urlCreator' => fn($action, $model, $key, $index) => Url::to([$action, 'id' => $key]),
        ],
    ],
]) ?>
