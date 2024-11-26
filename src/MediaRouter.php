<?php

namespace RonasIT\Media;

use Illuminate\Support\Facades\Route;
use RonasIT\Media\Enums\MediaRouteActionEnum;
use RonasIT\Media\Http\Controllers\MediaController;

class MediaRouter
{
    public static bool $isBlockedBaseRoutes = false;

    public function media()
    {
        return function (MediaRouteActionEnum ...$options)  {

            MediaRouter::$isBlockedBaseRoutes = true;

            $defaultOptions = [
                'create' => false,
                'delete' => false,
                'bulk_create' => false,
                'search' => false,
            ];

            if (empty($options)){
                $options = array_fill_keys(array_keys($defaultOptions), true);
            } else {
                $options = array_column($options, 'value');
                $options = array_fill_keys(array_values($options), array_keys($options));
                $options = array_merge($defaultOptions, array_fill_keys(array_keys($options), true));
            }

            $this->controller(MediaController::class)->group(function () use ($options) {
                when($options['create'], fn () => $this->post('media', 'create')->name('media.create'));
                when($options['delete'], fn () => $this->delete('media/{id}', 'delete')->name('media.delete'));
                when($options['bulk_create'], fn () => $this->post('media/bulk', 'bulkCreate')->name('media.create.bulk'));
                when($options['search'], fn () => $this->get('media', 'search')->name('media.search'));
            });
        };
    }
}