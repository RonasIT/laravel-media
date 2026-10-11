<?php

namespace RonasIT\Media\Tests\Support\Resources;

use RonasIT\Media\Http\Resources\MediaResource;

class CustomMediaResource extends MediaResource
{
    public function toArray($request): array
    {
        return [
            ...parent::toArray($request),
            'owner_id' => $this->resource->owner_id,
        ];
    }
}
