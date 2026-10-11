<?php

namespace RonasIT\Media\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use RonasIT\Media\Contracts\Resources\MediaCollectionContract;
use RonasIT\Media\Contracts\Resources\MediaListResourceContract;
use RonasIT\Media\Contracts\Resources\MediaResourceContract;

class MediaCollection extends ResourceCollection implements MediaCollectionContract, MediaListResourceContract
{
    public static $wrap = null;

    protected function collects(): string
    {
        return get_class(app(MediaResourceContract::class, ['resource' => null]));
    }
}
