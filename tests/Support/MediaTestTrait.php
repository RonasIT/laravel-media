<?php

namespace RonasIT\Media\Tests\Support;

use RonasIT\Media\Contracts\Services\MediaServiceContract;
use RonasIT\Support\Traits\MockTrait;

trait MediaTestTrait
{
    use MockTrait;

    public function mockGenerateFilename(...$fileNames): void
    {
        if (empty($fileNames)) {
            $fileNames = ['file.png'];
        }

        $this->mockClass(
            class: (string) app(MediaServiceContract::class),
            callChain: array_map(fn ($fileName) => [
                'function' => 'generateName',
                'arguments' => [],
                'result' => $fileName,
            ], $fileNames)
        );
    }
}
