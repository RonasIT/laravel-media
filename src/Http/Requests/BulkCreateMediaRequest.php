<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Contracts\Requests\BulkCreateMediaRequestContract;
use RonasIT\Support\Http\BaseRequest;

class BulkCreateMediaRequest extends BaseRequest implements BulkCreateMediaRequestContract
{
    public function rules(): array
    {
        $types = implode(',', config('media.permitted_types'));
        $maxFileSize = config('media.max_file_size');

        return [
            'media' => 'required|array',
            'media.*' => 'array',
            'media.*.file' => "file|required|max:{$maxFileSize}|mimes:{$types}",
            'media.*.meta' => 'array',
            'media.*.is_public' => 'boolean',
        ];
    }
}
