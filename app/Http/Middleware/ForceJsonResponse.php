<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header('Accept');
        if ($header != 'application/json') {
            return response(['message' => 'Only JSON requests are allowed'], 406);
        }

        if (!$request->isMethod('post')) return $next($request);

        $header_content = $request->header('Content-type');
        if ($header_content != 'application/json') {
            return response(['message' => 'Only JSON requests are allowed'], 406);
        }

        return $next($request);
    }
}
