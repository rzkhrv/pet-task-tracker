<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        $isNotGetRequest = $request->isMethod('GET') === false;
        $hasContentTypeIsNotJson = $request->headers->get('Content-Type') !== 'application/json';

        if ($isNotGetRequest && $hasContentTypeIsNotJson) {
            abort(400, 'Request is not a valid JSON request.');
        }

        return $next($request);
    }
}
