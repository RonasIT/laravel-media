<?php

namespace RonasIT\Media\Contracts\Resources;

interface MediaResourceContract
{
    public function toArray($request): array;
}
