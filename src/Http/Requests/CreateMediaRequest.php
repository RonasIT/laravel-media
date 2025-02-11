<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Contracts\Requests\CreateMediaRequestContract;
use RonasIT\Support\Http\BaseRequest;

class CreateMediaRequest extends BaseRequest implements CreateMediaRequestContract
{
    public function rules(): array
    {
        $types = implode(',', config('media.permitted_types'));
        $maxFileSize = config('media.max_file_size');

        return [
            'file' => "file|required|max:{$maxFileSize}|mimes:{$types}",
            'meta' => 'array',
            'is_public' => 'boolean',
        ];
    }
}
