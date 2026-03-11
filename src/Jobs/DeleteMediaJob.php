<?php

namespace RonasIT\Media\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RonasIT\Media\Contracts\Services\MediaServiceContract;

class DeleteMediaJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected int $mediaID,
    ) {
    }

    public function handle(): void
    {
        $mediaService = app(MediaServiceContract::class);

        if (!$mediaService->exists($this->mediaID)) {
            return;
        }

        $mediaService->delete($this->mediaID);
    }
}
