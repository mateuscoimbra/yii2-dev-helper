<?php
/**
 * Arquivo: config/params.php
 * Descrição: Parâmetros globais e reutilizáveis da aplicação Yii2.
 * Uso: Disponível globalmente via Yii::$app->params['chave']
 */

return [

    // =============================
    // 📌 Identidade do Sistema
    // =============================
    'appName' => 'Sistema de Gestão Yii2',
    'appAbbreviation' => 'SGY',
    'appVersion' => '1.0.0',
    'appDescription' => 'Sistema completo com Yii2 Framework',

    // =============================
    // 🌐 Idioma e Região
    // =============================
    'defaultLanguage' => 'pt-BR',
    'defaultTimezone' => 'America/Sao_Paulo',
    'dateFormat' => 'dd/MM/yyyy',
    'datetimeFormat' => 'dd/MM/yyyy HH:mm',
    'currencyCode' => 'BRL',
    'locale' => 'pt_BR',

    // =============================
    // 👤 Sessão e Login
    // =============================
    'sessionTimeout' => 3600, // 1 hora
    'rememberMeDuration' => 60 * 60 * 24 * 30, // 30 dias

    // =============================
    // 📧 E-mail Padrão
    // =============================
    'adminEmail' => 'admin@exemplo.com',
    'supportEmail' => 'suporte@exemplo.com',
    'noReplyEmail' => 'noreply@exemplo.com',

    // =============================
    // 🖼️ Uploads e Caminhos
    // =============================
    'uploadPath' => '@webroot/uploads',
    'uploadUrl' => '@web/uploads',
    'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'pdf', 'docx'],
    'maxUploadSize' => 1024 * 1024 * 10, // 10 MB

    // =============================
    // 🔐 Segurança
    // =============================
    'passwordMinLength' => 8,
    'tokenExpire' => 3600,
    'enableTwoFactorAuth' => false,

    // =============================
    // 🧪 Ambiente e Debug
    // =============================
    'environment' => YII_ENV,
    'debug' => YII_DEBUG,

    // =============================
    // 📦 APIs externas
    // =============================
    'apiKeys' => [
        'googleMaps' => 'SUA-CHAVE-GOOGLE',
        'auth0' => 'SUA-CHAVE-AUTH0',
        'sendgrid' => 'SUA-CHAVE-SENDGRID',
    ],

    // =============================
    // 🎨 Layouts e UI
    // =============================
    'themeColor' => '#004080',
    'dashboardLayout' => 'main-dashboard',
    'defaultAvatarUrl' => '@web/images/avatar-default.png',

    // =============================
    // 📊 Configurações de Dashboard
    // =============================
    'defaultDashboardWidgets' => [
        'usersOnline' => true,
        'recentActivity' => true,
        'notifications' => true,
    ],

    // =============================
    // 📁 Módulos Ativos
    // =============================
    'enabledModules' => [
        'admin' => true,
        'report' => true,
        'api' => true,
    ],

    // =============================
    // 🔗 Links Úteis
    // =============================
    'externalLinks' => [
        'documentacao' => 'https://www.yiiframework.com/doc/guide/2.0/pt-br',
        'github' => 'https://github.com/seu-projeto',
        'ajuda' => '/site/ajuda',
    ],

    // =============================
    // 🧪 Chaves e Tokens Dinâmicos (use getEnv em produção)
    // =============================
    'envSecretKey' => getenv('APP_SECRET') ?: 'dev-secret',

    // =============================
    // ⚠️ Parâmetros personalizados de módulos internos
    // =============================
    'moduloFinanceiro' => [
        'moedaPadrao' => 'BRL',
        'jurosPadrao' => 1.5,
        'diasCarencia' => 10,
    ],

];
