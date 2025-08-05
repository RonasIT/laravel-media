<?php

namespace RonasIT\Media\Http\Middlewares;

use Closure;
use RonasIT\Media\MediaRouter;
use RonasIT\Media\MediaServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckManuallyRegisteredRoutesMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!config('media.auto_routes_enabled')) {
            throw new NotFoundHttpException('Not found.');
        }
        
        if (MediaRouter::$isBlockedBaseRoutes) {
            throw new NotFoundHttpException('Not found.');
        }

        return $next($request);
    }
}