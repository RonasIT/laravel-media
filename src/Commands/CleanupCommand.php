<?php

namespace RonasIT\Media\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class CleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:cleanup
        {--delete-all : Ignore `is_public` flag }
        {--public : Delete only records with `is_public` flag set to true}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete media records where the referenced `owner_id` no longer exists. By default, deletes records with `is_public` flag set to false.';

    public function handle(): void
    {
        $deletedNumber = $this->deleteRecords();

        $this->info("Deleted $deletedNumber record(s).");
    }

    protected function deleteRecords(): int
    {
        try {
            $query = DB::table('media')->whereNull('owner_id');

            if ($this->option('delete-all')) {
                return $query->delete();
            }

            return $query
                ->where('is_public', $this->option('public'))
                ->delete();
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            throw new Exception('Failed to delete records.');
        }
    }
}