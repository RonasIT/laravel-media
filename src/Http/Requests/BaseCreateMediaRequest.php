<?php

namespace RonasIT\Media\Http\Requests;

use Illuminate\Support\Arr;
use RonasIT\Support\BaseRequest;

class BaseCreateMediaRequest extends BaseRequest
{
    public function rules(): array
    {
        $previewProviders = implode(',', $this->getPreviewProviders());

        return [
            'preview_drivers' => 'array',
            'preview_drivers.*' => "string|in:{$previewProviders}",
        ];
    }

    protected function getPreviewProviders(): array
    {
        return Arr::map(config('media.drivers'), fn ($driver) => $driver->value);
    }
}
