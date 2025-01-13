<?php

namespace RonasIT\Media\Http\Resources;

use RonasIT\Media\Contracts\Resources\MediaCollectionContract;
use RonasIT\Media\Contracts\Resources\MediaListResourceContract;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaCollection extends ResourceCollection implements MediaCollectionContract, MediaListResourceContract
{
    public $collects = MediaResource::class;

    public static $wrap = null;
}
