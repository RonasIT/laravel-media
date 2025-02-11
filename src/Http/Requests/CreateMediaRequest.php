<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Contracts\Requests\CreateMediaRequestContract;

class CreateMediaRequest extends BaseCreateMediaRequest implements CreateMediaRequestContract
{
    public function rules(): array
    {
        $types = implode(',', config('media.permitted_types'));
        $maxFileSize = config('media.max_file_size');

        $rules = [
            'file' => "file|required|max:{$maxFileSize}|mimes:{$types}",
            'meta' => 'array',
            'is_public' => 'boolean',
        ];

        return array_merge(parent::rules(), $rules);
    }
}
