<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/capsule.php';

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

// 中间件
use App\Middleware\HttpCros as HttpCros;
use App\Middleware\Interception as Interception;
use App\Middleware\Auth as Auth;

//创建验证器规则
$hours = v::intVal()->between(1, 12);
$user = v::length(1, 20);

$app->add(new Validation(['user' => $user]))
    ->add(HttpCros::class);

$app->post('/register', 'App\User:register');

$app->post('/overtime', 'App\WorkOvertime:index')
    ->add(Interception::class)
    ->add(new Validation(['hours' => $hours]))
    ->add(Auth::class);

$app->get('/overtime', 'App\WorkOvertime:overtimeList')
    ->add(Auth::class);

$app->get('/surplus', 'App\WorkOvertime:overtimeSurplus')
    ->add(Auth::class);

//try {
$app->run();
//} catch (\Exception $e) {
//    $res = $app->getContainer()->get('response');
//    if ($e instanceof \Slim\Exception\MethodNotAllowedException) {
//        return $res->withStatus(405);
//    }
//    if ($e instanceof \Slim\Exception\NotFoundException) {
//        return $res->withStatus(404);
//    }
//    return $res->withJson(['msg' => $e->getMessage()], 404);
//}
