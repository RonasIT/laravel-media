<?php

namespace RonasIT\Media\Http\Requests;

use Illuminate\Support\Arr;
use RonasIT\Support\BaseRequest;

class BaseCreateMediaRequest extends BaseRequest
{
    protected function getPreviewProviders(): array
    {
        return Arr::map(config('media.drivers'), fn ($driver) => $driver->value);
    }
}