<?php

namespace RonasIT\Media\Http\Resources;

use Illuminate\Http\Resources\Attributes\Collects;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use ReflectionClass;
use RonasIT\Media\Contracts\Resources\MediaCollectionContract;
use RonasIT\Media\Contracts\Resources\MediaListResourceContract;
use RonasIT\Media\Contracts\Resources\MediaResourceContract;

class MediaCollection extends ResourceCollection implements MediaCollectionContract, MediaListResourceContract
{
    public static $wrap = null;

    protected function collects(): string
    {
        $attribute = Arr::first((new ReflectionClass($this))->getAttributes(Collects::class));

        return $attribute?->newInstance()->class
            ?? $this->collects
            ?? get_class(app(MediaResourceContract::class, ['resource' => null]));
    }
}
