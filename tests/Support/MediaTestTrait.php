<?php

namespace RonasIT\Media\Tests\Support;

use Illuminate\Support\Arr;
use RonasIT\Media\Services\MediaService;
use RonasIT\Support\Traits\MockTrait;

trait MediaTestTrait
{
    use MockTrait;

    public function mockGenerateFilename(array ...$mockData): void
    {
        if (empty($mockData)) {
            $mockData = [
                [
                    'argument' => 'file.png',
                    'result' => 'hashed_file.png',
                ],
            ];
        }

        $this->mockClass(
            class: MediaService::class,
            callChain: array_map(fn ($fileName) => $this->functionCall(
                name: 'generateName',
                arguments: Arr::wrap($fileName['argument']),
                result: $fileName['result'],
            ), $mockData),
        );
    }
}
