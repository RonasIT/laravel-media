<?php

namespace RonasIT\Media\Tests\Support;

use RonasIT\Media\Services\MediaService;
use RonasIT\Support\Traits\MockClassTrait;

trait MediaTestTrait
{
    use MockClassTrait;

    public function mockGenerateFilename(...$fileNames): void
    {
        if (empty($fileNames)) {
            $fileNames = ['file.png'];
        }

        $this->mockClass(
            class: MediaService::class,
            callChain: array_map(fn ($fileName) => [
                'method' => 'generateName',
                'arguments' => [],
                'result' => $fileName,
            ], $fileNames)
        );
    }
}
