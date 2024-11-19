<?php

namespace RonasIT\Media;

use Illuminate\Support\Facades\Route;
use RonasIT\Media\Http\Controllers\MediaController;

class MediaRouter
{
    public static bool $isBlockedBaseRoutes = false;

    public function media()
    {
        return function (array $options = [])  {

            MediaRouter::$isBlockedBaseRoutes = true;

            $options = [
                'create' => $options['create'] ?? true,
                'delete' => $options['delete'] ?? true,
                'bulk_create' => $options['bulk_create'] ?? true,
                'search' => $options['search'] ?? true,
            ];

            $this->controller(MediaController::class)->group(function () use ($options) {
                when($options['create'], fn () => $this->post('media', 'create')->name('media.create'));
                when($options['delete'], fn () => $this->delete('media/{id}', 'delete')->name('media.delete'));
                when($options['bulk_create'], fn () => $this->post('media/bulk', 'bulkCreate')->name('media.create.bulk'));
                when($options['search'], fn () => $this->get('media', 'search')->name('media.search'));
            });
        };
    }
}