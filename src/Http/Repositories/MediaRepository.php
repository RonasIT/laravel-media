<?php

namespace RonasIT\Media\Http\Repositories;

use RonasIT\Media\Models\Media;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function getSearchResults(): LengthAwarePaginator
    {
        $this->query->applyMediaPermissionRestrictions();

        return parent::getSearchResults();
    }
}
