<?php
/**
 * Yii2 - Snippet Base para ActiveForm, GridView e DetailView
 * Este exemplo serve como referência ultra completa e comentada para uso em projetos Yii2.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

?>

<!-- ====== ActiveForm::begin() ====== -->
<?php
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
    ],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n{error}",
        'labelOptions' => ['class' => 'col-lg-4 control-label text-right'],
        'options' => ['class' => 'form-group row'],
    ],
]);
?>

<!-- ====== Campo textInput Ultra Completo ====== -->
<?= $form->field($model, 'email', [
    'options' => ['class' => 'form-group has-feedback'],
    'template' => "{label}\n{input}\n{hint}\n{error}",
    'labelOptions' => ['class' => 'control-label'],
    'hintOptions' => ['class' => 'form-text text-muted'],
])->textInput([
    'type' => 'email',
    'placeholder' => 'Digite seu e-mail',
    'maxlength' => 255,
    'autofocus' => true,
    'class' => 'form-control',
])->label('Seu E-mail')->hint('Utilize seu e-mail institucional.') ?>

<!-- Outros campos -->
<?= $form->field($model, 'senha')->passwordInput(['class' => 'form-control']) ?>
<?= $form->field($model, 'genero')->dropDownList(['M' => 'Masculino', 'F' => 'Feminino'], ['prompt' => 'Selecione']) ?>
<?= $form->field($model, 'aceite')->checkbox(['label' => 'Aceito os termos']) ?>

<!-- ====== Botão de Envio ====== -->
<div class="form-group">
    <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

<!-- ====== DetailView::widget() ====== -->
<?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'table table-bordered'],
    'attributes' => [
        'id',
        'email:email',
        [
            'label' => 'Status',
            'value' => $model->ativo ? 'Ativo' : 'Inativo',
        ],
    ],
]) ?>

<!-- ====== GridView::widget() ====== -->
<?php
$dataProvider = new ActiveDataProvider([
    'query' => \app\models\Usuario::find(),
    'pagination' => ['pageSize' => 10],
]);
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'email:email',
        [
            'attribute' => 'status',
            'value' => fn($model) => $model->status ? 'Ativo' : 'Inativo',
            'filter' => ['1' => 'Ativo', '0' => 'Inativo'],
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]) ?>
