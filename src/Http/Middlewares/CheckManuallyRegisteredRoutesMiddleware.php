<?php

namespace RonasIT\Media\Http\Middlewares;

use Closure;
use RonasIT\Media\MediaRouter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Config;

class CheckManuallyRegisteredRoutesMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Config::get('media.api_enable')) {
            throw new NotFoundHttpException('Not found.');
        }

        return $next($request);
    }
}