<?php

namespace RonasIT\Media\Tests\Support\Resources;

use RonasIT\Media\Http\Resources\MediaCollection;

class ExplicitCollectsMediaCollection extends MediaCollection
{
    public $collects = CustomMediaResource::class;
}
