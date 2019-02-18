<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/capsule.php';

$dotEnv = Dotenv\Dotenv::create(__DIR__ . '/..');
$dotEnv->load();

// 显示debug错误信息
$configuration = getenv('APP_ENV') === 'prod' ? [] : [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];