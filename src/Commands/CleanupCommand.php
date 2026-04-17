<?php

namespace RonasIT\Media\Commands;

use Illuminate\Console\Command;
use RonasIT\Media\Jobs\DeleteMediaJob;
use RonasIT\Media\Models\Media;
use RonasIT\Media\Services\MediaService;

class CleanupCommand extends Command
{
    protected $signature = 'media:cleanup
        {--delete-all : Ignore `is_public` flag }
        {--public : Delete only records with `is_public` flag set to true}
    ';

    protected $description = 'Delete media records where the referenced `owner_id` no longer exists. By default, deletes records with `is_public` flag set to false.';

    public function handle(): void
    {
        $dispatchedJobsCount = 0;

        $where = $this->getWhereOptions();

        // TODO: use laravel-helpers each
        app(MediaService::class)
            ->lazyById($where, 100)
            ->each(function (Media $media) use (&$dispatchedJobsCount) {
                DeleteMediaJob::dispatch($media->id);

                $dispatchedJobsCount++;
            });

        $this->info("Successfully dispatched {$dispatchedJobsCount} job(s) for deletion.");
    }

    protected function getWhereOptions(): array
    {
        $where = [
            'owner_id' => null,
        ];

        if (!$this->option('delete-all')) {
            $where['is_public'] = $this->option('public');
        }

        return $where;
    }
}
