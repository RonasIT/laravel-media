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
            'blur_hash' => $this->when(!is_null($this->resource->blur_hash), $this->resource->blur_hash),
            'preview' => MediaResource::make($this->whenLoaded('preview')),
        ];
    }
}
