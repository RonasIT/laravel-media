<?php

namespace RonasIT\Media\Tests\Support\Resources;

use RonasIT\Media\Http\Resources\MediaCollection;

class CustomMediaCollection extends MediaCollection
{
    public function with($request): array
    {
        return ['is_custom' => true];
    }
}
