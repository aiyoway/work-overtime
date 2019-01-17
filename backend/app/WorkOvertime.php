<?php

namespace App;


use Psr\Container\ContainerInterface;

class WorkOvertime
{
    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
    }

    public function index($req, $res)
    {
        $params = $req->getParsedBody();
        return $res->withJson(['status' => 0]);
    }
}