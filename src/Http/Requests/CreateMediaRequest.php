<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Contracts\Requests\CreateMediaRequestContract;
use RonasIT\Support\BaseRequest;

class CreateMediaRequest extends BaseRequest implements CreateMediaRequestContract
{
    public function rules(): array
    {
        $types = implode(',', config('media.permitted_types'));
        $maxFileSize = config('media.max_file_size');
        $previewProviders = implode(',', $this->getPreviewProviders());

        return [
            'file' => "file|required|max:{$maxFileSize}|mimes:{$types}",
            'preview_drivers' => 'array',
            'preview_drivers.*' => "string|in:{$previewProviders}",
            'meta' => 'array',
            'is_public' => 'boolean',
        ];
    }

    protected function getPreviewProviders(): array
    {
        return config('media.drivers');
    }
}
