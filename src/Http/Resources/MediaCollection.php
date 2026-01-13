<?php

namespace RonasIT\Media\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use RonasIT\Media\Contracts\Resources\MediaCollectionContract;
use RonasIT\Media\Contracts\Resources\MediaListResourceContract;

class MediaCollection extends ResourceCollection implements MediaCollectionContract, MediaListResourceContract
{
    public $collects = MediaResource::class;

    public static $wrap = null;
}
