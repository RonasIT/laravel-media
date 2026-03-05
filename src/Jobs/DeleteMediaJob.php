<?php

namespace RonasIT\Media\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use RonasIT\Media\Services\MediaService;

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
        if (!app(MediaService::class)->exists($this->mediaID)) {
            return;
        }

        app(MediaServiceContract::class)->delete($this->mediaID);
    }
}
