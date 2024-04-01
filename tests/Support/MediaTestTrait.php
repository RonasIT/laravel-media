<?php

namespace RonasIT\Media\Tests\Support;

use RonasIT\Media\Services\MediaService;
use RonasIT\Support\Traits\MockClassTrait;

trait MediaTestTrait
{
    use MockClassTrait;

    public function mockGenerateFilename($callsCount = 1): void
    {
        $this->mockClass(
            class: MediaService::class,
            callChain: array_fill(0, $callsCount, [
                'method' => 'generateName',
                'arguments' => [],
                'result' => 'file.png',
            ])
        );
    }
}
