<?php

require 'vendor/autoload.php';

// 显示错误信息
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

use Respect\Validation\Validator as v;
use DavidePastore\Slim\Validation\Validation as Validation;

//创建验证器
$times = v::optional(v::numeric());
$validators = [
    'times' => $times
];


//$app->get('/hello/{name}', function ($request, $response, $args) {
//    return $response->getBody()->write("Hello, " . $args['name']);
//});

$app->post('/overtime', 'App\WorkOvertime:index')->add(new Validation($validators));
$app->get('/verification', 'App\WorkOvertime:index');
$app->post('/register', 'App\User:register');

$app->run();