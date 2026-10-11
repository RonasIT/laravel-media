<?php

namespace RonasIT\Media\Tests\Support;

use Illuminate\Http\UploadedFile;
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

    public function mockBulkGenerateFilename(): void
    {
        $this->mockGenerateFilename(
            [
                'argument' => 'file.png',
                'result' => 'hashed_file_1.png',
            ],
            [
                'argument' => 'file2.png',
                'result' => 'hashed_file_2.png',
            ],
        );
    }

    public function getBulkCreateData(): array
    {
        return [
            'media' => [
                [
                    'file' => UploadedFile::fake()->image('file.png', 600, 600),
                    'meta' => ['test1'],
                ],
                [
                    'file' => UploadedFile::fake()->image('file2.png', 600, 600),
                    'meta' => ['test2'],
                ],
            ],
        ];
    }
}
