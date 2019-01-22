<?php

namespace App\middleware;


class HttpCros
{
    public function __invoke($request, $response, $next)
    {
        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        if ($request->isOptions()) {
            $response = $response->withHeader('Allow', 'GET, POST, PUT, DELETE, OPTIONS');
            if ($request->hasHeader('Access-Control-Request-Headers')) {
                $response = $response->withHeader('Access-Control-Allow-Headers', $request->getHeader('Access-Control-Request-Headers'));
            }
            return $response->withStatus(200);
        }
        return $next($request, $response);
    }
}