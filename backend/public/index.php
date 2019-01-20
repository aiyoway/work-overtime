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
use App\Middleware\Interception as Interception;
use App\Middleware\Auth as Auth;

//创建验证器规则
$hours = v::numeric();
$user = v::length(1, 20);

$app->post('/register', 'App\User:register')
    ->add(Interception::class)
    ->add(new Validation(['user' => $user]));

$app->post('/overtime', 'App\WorkOvertime:index')
    ->add(Interception::class)
    ->add(new Validation(['user' => $user, 'hours' => $hours]))
    ->add(Auth::class);

$app->get('/surplus', 'App\WorkOvertime:overtimeSurplus')
    ->add(Auth::class);

$app->run();