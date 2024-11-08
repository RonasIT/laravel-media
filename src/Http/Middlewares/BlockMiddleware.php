<?php

namespace RonasIT\Media\Http\Middlewares;

use Closure;
use RonasIT\Media\MediaServiceProvider;

class BlockMiddleware
{
    public function handle($request, Closure $next)
    {
        if (MediaServiceProvider::$isBlockedBaseRoutes) {
            abort(404);
        }

        return $next($request);
    }
}