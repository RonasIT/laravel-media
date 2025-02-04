<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Contracts\Requests\CreateMediaRequestContract;

class CreateMediaRequest extends BaseCreateMediaRequest implements CreateMediaRequestContract
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


}
