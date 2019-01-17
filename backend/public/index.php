<?php

require '../vendor/autoload.php';

// 显示debug错误信息
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

use Respect\Validation\Validator as v;
use DavidePastore\Slim\Validation\Validation as Validation;

// 验证拦截中间件
$interception = new \App\Middleware\Interception();

//创建验证器规则
$times = v::optional(v::numeric());
$user = v::length(1, 20)->notOptional();

$app->post('/register', 'App\User:register')
    ->add($interception)
    ->add(new Validation(['user' => $user]));

$app->post('/overtime', 'App\WorkOvertime:index')
    ->add($interception)
    ->add(new Validation(['times' => $times]));

$app->get('/verification', 'App\WorkOvertime:index');

$app->run();