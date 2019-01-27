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
        $pwd = isset($params['password']) ? $params['password'] : getenv('DEFAULT_PWD');
        $insert['password'] = md5($pwd);
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

    public function login($req, $res)
    {
        $params = $req->getParsedBody();
        $user = $params['user'];
        $info = DB::table('wo_users')->where('user', $user)->first();
        if (!$info || (!empty($info->password) && $info->password != md5($params['password']))) {
            return $res->withJson([
                'msg' => "用户名或密码错误"
            ], 400);
        }
        $token = [
            'iat' => time(), //签发时间
            'data' => [
                'id' => $info->id
            ]
        ];
        $jwt = JWT::encode($token, getenv('APP_KEY'));
        return $res->withJson(['token' => $jwt], 200);
    }

    public function changePwd($req, $res)
    {
        $params = $req->getParsedBody();
        $user = $params['user'];
        $info = DB::table('wo_users')->where('user', $user)->first();
        if (!$info || $info->password != md5($params['password'])) {
            return $res->withJson([
                'msg' => "用户名或密码错误"
            ], 400);
        }
        DB::table('wo_users')->where('user', $user)->update(['password' => $params['newPWD']]);
        $token = [
            'iat' => time(), //签发时间
            'data' => [
                'id' => $info->id
            ]
        ];
        $jwt = JWT::encode($token, getenv('APP_KEY'));
        return $res->withJson(['token' => $jwt], 200);
    }
}