<?php

namespace App;

use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager as DB;

class User
{
    public function register($req, $res)
    {
        $params = $req->getParsedBody();
        $user = $params['user'];
        $exists = DB::table('wo_users')->where('user', $user)->first();
        if ($exists) {
            return $res->withJson([
                'msg' => "用户已存在"
            ], 400);
        }
        $insert = [
            'user' => $user,
            'created' => time()
        ];
        if (!empty($params['password'])) {
            $insert['password'] = md5($params['password']);
        }
        $id = DB::table('wo_users')->insertGetId($insert);
        $token = [
            'iat' => time(), //签发时间
            'data' => [
                'id' => $id
            ]
        ];
        $jwt = JWT::encode($token, getenv('APP_KEY'));
        return $res->withJson(['token' => $jwt], 201);
    }
}