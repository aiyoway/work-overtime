<?php

namespace App;

use Psr\Container\ContainerInterface;
use Illuminate\Database\Capsule\Manager as DB;

class WorkOvertime
{
    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
    }

    public function index($req, $res)
    {
        $params = $req->getParsedBody();
        $user = $this->ci->get('user');
        DB::table('wo_times')->insert([
            'user_id' => $user->id,
            'hours' => $params['hours'],
            'date' => empty($params['date']) ? time() : strtotime($params['date']),
            'created' => time()
        ]);
        return $res->withStatus(201);
    }
}