<?php
/**
 * Script de Debug de Login
 * Execute: php debug-login.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

echo "===========================================\n";
echo "  DEBUG DE LOGIN - SISTEMA OIA\n";
echo "===========================================\n\n";

// E-mail e senha para testar
$email = 'admin@oia.com.br';
$senha = 'admin123';

echo "Testando login com:\n";
echo "E-mail: {$email}\n";
echo "Senha: {$senha}\n\n";

// 1. Verificar se usuário existe
echo "1. Procurando usuário no banco...\n";
$user = \app\models\Usuario::findOne(['email' => $email]);

if (!$user) {
    echo "   ❌ ERRO: Usuário não encontrado no banco!\n";
    echo "   Execute o SQL de inserção novamente.\n\n";
    exit(1);
}

echo "   ✅ Usuário encontrado!\n";
echo "   ID: {$user->id}\n";
echo "   Nome: {$user->nome_completo}\n";
echo "   Status: {$user->status}\n";
echo "   Hash armazenado: {$user->senha_hash}\n\n";

// 2. Testar validação de senha
echo "2. Testando validação de senha...\n";
$valida = $user->validatePassword($senha);

if ($valida) {
    echo "   ✅ Senha VÁLIDA!\n\n";
} else {
    echo "   ❌ Senha INVÁLIDA!\n";
    echo "   O hash no banco não corresponde à senha.\n\n";
    
    // Gerar novo hash
    echo "3. Gerando novo hash para a senha '{$senha}'...\n";
    $novoHash = Yii::$app->security->generatePasswordHash($senha);
    echo "   Novo hash: {$novoHash}\n\n";
    
    echo "4. Execute este SQL para corrigir:\n\n";
    echo "UPDATE usuario SET senha_hash = '{$novoHash}' WHERE email = '{$email}';\n\n";
    exit(1);
}

// 3. Testar método findByEmail
echo "3. Testando método findByEmail...\n";
$userByEmail = \app\models\Usuario::findByEmail($email);

if (!$userByEmail) {
    echo "   ❌ ERRO: findByEmail não encontrou o usuário!\n";
    echo "   Verifique se o status é 'ativo'.\n\n";
    exit(1);
}

echo "   ✅ findByEmail funcionou!\n\n";

// 4. Testar LoginForm
echo "4. Testando LoginForm...\n";
$model = new \app\models\LoginForm();
$model->email = $email;
$model->senha = $senha;

if ($model->validate()) {
    echo "   ✅ Validação do formulário OK!\n\n";
} else {
    echo "   ❌ Erros de validação:\n";
    print_r($model->errors);
    echo "\n";
    exit(1);
}

// 5. Testar login completo
echo "5. Testando login completo...\n";
if ($model->login()) {
    echo "   ✅ LOGIN BEM-SUCEDIDO!\n";
    echo "   O problema não é com a lógica de login.\n";
    echo "   Verifique o JavaScript ou o formulário HTML.\n\n";
} else {
    echo "   ❌ Falha no login!\n";
    echo "   Erros:\n";
    print_r($model->errors);
    echo "\n";
}

echo "===========================================\n";
echo "  FIM DO DEBUG\n";
echo "===========================================\n";