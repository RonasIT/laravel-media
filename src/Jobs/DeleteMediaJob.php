<?php

namespace RonasIT\Media\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use RonasIT\Media\Models\Media;
use RonasIT\Media\Services\MediaService;

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
        app(MediaService::class)->delete($this->media->id);

        Storage::delete($this->media->name);
    }
}