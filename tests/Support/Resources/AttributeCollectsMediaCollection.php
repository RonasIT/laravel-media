<?php

namespace RonasIT\Media\Tests\Support\Resources;

use Illuminate\Http\Resources\Attributes\Collects;
use RonasIT\Media\Http\Resources\MediaCollection;

#[Collects(CustomMediaResource::class)]
class AttributeCollectsMediaCollection extends MediaCollection
{
}
