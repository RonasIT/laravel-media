<?php

namespace RonasIT\Media\Repositories;

use Illuminate\Support\LazyCollection;
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

    public function lazyById(array $where, int $chunkSize): LazyCollection
    {
        return $this->getQuery($where)->lazyById($chunkSize);
    }
}
