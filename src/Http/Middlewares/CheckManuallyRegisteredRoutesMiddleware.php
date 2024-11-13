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
        if (MediaRouter::$isBlockedBaseRoutes) {
            throw new NotFoundHttpException('Not found.');
        }

        return $next($request);
    }
}