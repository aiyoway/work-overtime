<?php

namespace App;

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
//        if ($params['password']) {
//            $insert['password'] = md5($params['password']);
//        }
        DB::table('wo_users')->insert($insert);
        return $res->withJson(['user' => $user]);
    }
}