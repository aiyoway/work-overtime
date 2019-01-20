<?php

namespace App\middleware;

use Illuminate\Database\Capsule\Manager as DB;
use Psr\Container\ContainerInterface;

class Auth
{
    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
    }

    public function __invoke($request, $response, $next)
    {
        $params = $request->isGet() ? $request->getQueryParams() : $request->getParsedBody();
        if (!$user = DB::table('wo_users')->where('user', $params['user'])->first()) {
            return $response->withJson(['msg' => "用户名或密码错误"], 401);
        }
        if ($user->password) {
            if (!empty($params['password']) || $user->password != md5($params['password'])) {
                return $response->withJson(['msg' => "用户名或密码错误"], 400);
            }
        }
        $this->ci['user'] = $user;
        return $next($request, $response);
    }
}