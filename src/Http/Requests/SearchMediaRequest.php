<?php

namespace RonasIT\Media\Http\Requests;

use RonasIT\Media\Contracts\Requests\SearchMediaRequestContract;
use RonasIT\Support\BaseRequest;

class SearchMediaRequest extends BaseRequest implements SearchMediaRequestContract
{
    public function rules(): array
    {
        return [
            'page' => 'integer',
            'per_page' => 'integer',
            'all' => 'integer',
            'query' => 'string',
            'order_by' => 'string',
            'desc' => 'boolean',
            'name' => 'string'
        ];
    }
}
