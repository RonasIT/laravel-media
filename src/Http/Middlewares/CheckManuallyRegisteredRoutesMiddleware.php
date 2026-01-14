<?php

namespace RonasIT\Media\Http\Middlewares;

use Closure;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
