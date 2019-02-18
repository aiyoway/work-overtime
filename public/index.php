<?php

require_once __DIR__.'/../bootstrap/bootstrap.php';

// 中间件
use App\Middleware\HttpCros as HttpCros;
use App\Middleware\Interception as Interception;
use App\Middleware\Auth as Auth;

use Respect\Validation\Validator as v;
use DavidePastore\Slim\Validation\Validation as Validation;

//创建验证器规则
$hours = v::intVal()->max(16)->notEmpty();
$user = v::length(1, 20);

$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$app->add(HttpCros::class);

$app->get('/', function ($req, $res) {
    return $res->write('App is running.');
});

$app->group('', function () {
    $this->post('/register', 'App\User:register');
    $this->post('/login', 'App\User:login');
    $this->post('/changePwd', 'App\User:changePwd');
})->add(Interception::class)
    ->add(new Validation(['user' => $user]));

$app->group('', function () use ($hours) {
    $this->get('/overtime', 'App\WorkOvertime:overtimeList')
        ->add(Interception::class);

    $this->post('/overtime', 'App\WorkOvertime:index')
        ->add(Interception::class)
        ->add(new Validation(['hours' => $hours]));

    $this->get('/surplus', 'App\WorkOvertime:overtimeSurplus')
        ->add(Interception::class);
})->add(Auth::class);

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
