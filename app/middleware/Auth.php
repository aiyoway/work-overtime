<?php

namespace App\middleware;

use Firebase\JWT\JWT;
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
        $authorization = $request->getHeader('Authorization');
        if (empty($authorization)) {
            return $response->withJson(['msg' => "缺少Authorization参数"], 400);
        }
        try {
            $decoded = JWT::decode($authorization[0], getenv('APP_KEY'), ['HS256']);
        } catch (\Exception $e) {
            return $response->withJson(['msg' => "token错误"], 400);
        }
        $this->ci['user'] = DB::table('wo_users')->where('id', $decoded->data->id)->first();
        return $next($request, $response);
    }
}