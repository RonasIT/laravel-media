<?php

namespace RonasIT\Media\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use RonasIT\Media\Models\Media;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property Media $model
 */
class MediaRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Media::class);
    }
}
