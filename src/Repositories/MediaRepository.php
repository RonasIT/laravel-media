<?php

namespace RonasIT\Media\Repositories;

use RonasIT\Media\Contracts\Repository\MediaRepositoryContract;
use RonasIT\Media\Models\Media;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property Media $model
 */
class MediaRepository extends BaseRepository implements MediaRepositoryContract
{
    public function __construct()
    {
        $this->setModel(Media::class);
    }
}
