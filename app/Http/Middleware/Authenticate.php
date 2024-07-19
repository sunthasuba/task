<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
        else {
            // For API requests, return null to prevent redirection.
            return null;
        }
    }

    protected function unauthenticated($request, array $guards)
{
    if ($request->expectsJson() || $request->is('api/*')) {
        return response()->json([
            'error' => 'Unauthenticated',
            'message' => 'Please provide a valid access token.',
        ], Response::HTTP_UNAUTHORIZED);
    }

    return redirect()->guest(route('login'));
}
}
