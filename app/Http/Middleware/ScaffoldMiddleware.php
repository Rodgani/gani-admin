<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use function PHPUnit\Framework\returnArgument;

class ScaffoldMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $scaffold = config("app.scaffold");
        if (!$scaffold) {
            throw new HttpException(404, 'Scaffolding not available.');
        }

        return $next($request);
    }
}
