<?php

namespace App\Middleware;


class Interception
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if ($request->getAttribute('has_errors')) {
            //There are errors, read them
            $errors = $request->getAttribute('errors');
            return $response->withJson($errors, 400);
        }
        $response = $next($request, $response);
        return $response;
    }
}