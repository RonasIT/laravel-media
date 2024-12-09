<?php

namespace RonasIT\Media\Http\Resources;

use RonasIT\Media\Contracts\Resources\MediaResourceContract;

class MediaResource extends BaseResource implements MediaResourceContract
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'link' => $this->resource->link,
            'name' => $this->resource->name,
            'is_public' => $this->resource->is_public,
            'meta' => $this->resource->meta,
            'preview' => MediaResource::make($this->whenLoaded('preview')),
        ];
    }
}
