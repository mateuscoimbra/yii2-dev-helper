<?php
/**
 * Script para corrigir hash de senha do usuário
 * Execute: php corrigir-senha.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

echo "===========================================\n";
echo "  CORRIGIR SENHA - SISTEMA OIA\n";
echo "===========================================\n\n";

$email = 'admin@oia.com.br';
$novaSenha = 'admin123';

echo "Procurando usuário: {$email}\n";

$user = \app\models\Usuario::findOne(['email' => $email]);

if (!$user) {
    echo "❌ Usuário não encontrado!\n";
    echo "Execute o SQL de inserção primeiro.\n\n";
    exit(1);
}

echo "✅ Usuário encontrado: {$user->nome_completo}\n";
echo "   ID: {$user->id}\n";
echo "   Hash antigo: {$user->senha_hash}\n\n";

// Gerar novo hash
echo "Gerando novo hash para senha: {$novaSenha}\n";
$novoHash = Yii::$app->security->generatePasswordHash($novaSenha);
echo "   Novo hash: {$novoHash}\n\n";

// Atualizar no banco
$user->senha_hash = $novoHash;

if ($user->save(false)) {
    echo "✅ Senha atualizada com sucesso!\n\n";
    
    // Testar a senha
    echo "Testando nova senha...\n";
    if ($user->validatePassword($novaSenha)) {
        echo "✅ Validação OK! Senha funcionando corretamente.\n\n";
        echo "Agora você pode fazer login com:\n";
        echo "E-mail: {$email}\n";
        echo "Senha: {$novaSenha}\n\n";
    } else {
        echo "❌ ERRO: Ainda não está validando!\n";
    }
} else {
    echo "❌ Erro ao salvar!\n";
    print_r($user->errors);
}

echo "===========================================\n";