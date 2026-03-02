<?php

namespace RonasIT\Media\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use RonasIT\Media\Models\Media;

class DeleteMediaJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected Media $media,
    ) {
    }

    public function handle(): void
    {
        app(MediaServiceContract::class)->delete($this->media->id);
    }
}
