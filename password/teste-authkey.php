<?php
/**
 * Script para testar AuthKey
 * Execute: php teste-authkey.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

echo "===========================================\n";
echo "  TESTE DE AUTHKEY - SISTEMA OIA\n";
echo "===========================================\n\n";

$email = 'admin@oia.com.br';

echo "1. Buscando usuário: {$email}\n";
$user = \app\models\Usuario::findByEmail($email);

if (!$user) {
    echo "❌ Usuário não encontrado!\n";
    exit(1);
}

echo "✅ Usuário encontrado!\n";
echo "   ID: {$user->id}\n";
echo "   Nome: {$user->nome_completo}\n\n";

echo "2. Testando getAuthKey()...\n";
$authKey = $user->getAuthKey();
echo "   AuthKey: {$authKey}\n";

if ($authKey) {
    echo "   ✅ AuthKey gerado com sucesso!\n\n";
} else {
    echo "   ❌ AuthKey é NULL!\n\n";
    exit(1);
}

echo "3. Testando validateAuthKey()...\n";
$valido = $user->validateAuthKey($authKey);

if ($valido) {
    echo "   ✅ Validação OK!\n\n";
} else {
    echo "   ❌ Validação FALHOU!\n\n";
    exit(1);
}

echo "4. Testando findIdentity()...\n";
$userById = \app\models\Usuario::findIdentity($user->id);

if ($userById) {
    echo "   ✅ findIdentity funcionou!\n";
    echo "   AuthKey: {$userById->getAuthKey()}\n\n";
} else {
    echo "   ❌ findIdentity FALHOU!\n\n";
    exit(1);
}

echo "✅ TODOS OS TESTES PASSARAM!\n";
echo "O problema NÃO é com authKey.\n\n";

echo "Agora teste fazer login pelo navegador.\n";
echo "Se ainda não funcionar, limpe o cache:\n";
echo "  - Feche e abra o navegador\n";
echo "  - Ou use modo anônimo\n";
echo "  - Ou limpe os cookies do localhost\n\n";

echo "===========================================\n";