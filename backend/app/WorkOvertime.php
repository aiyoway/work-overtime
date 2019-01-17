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
        if ($req->getAttribute('has_errors')) {
            //There are errors, read them
            $errors = $req->getAttribute('errors');
            return $res->withJson($errors,400);
        }
        $params = $req->getParsedBody();
        return $res->withJson(['status' => 0]);
    }
}