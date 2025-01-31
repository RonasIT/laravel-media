<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Contracts\Requests\BulkCreateMediaRequestContract;
use RonasIT\Support\BaseRequest;

class BulkCreateMediaRequest extends BaseRequest implements BulkCreateMediaRequestContract
{
    public function rules(): array
    {
        $types = implode(',', config('media.permitted_types'));
        $previewProviders = implode(',', $this->getPreviewProviders());
        $maxFileSize = config('media.max_file_size');

        return [
            'preview_drivers' => 'array',
            'preview_drivers.*' => "string|in:{$previewProviders}",
            'media' => 'required|array',
            'media.*' => 'array',
            'media.*.file' => "file|required|max:{$maxFileSize}|mimes:{$types}",
            'media.*.meta' => 'array',
            'media.*.is_public' => 'boolean',
        ];
    }

    protected function getPreviewProviders(): array
    {
        return config('media.drivers');
    }
}
