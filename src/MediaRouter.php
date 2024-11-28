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
        return function (MediaRouteActionEnum ...$options) {
            MediaRouter::$isBlockedBaseRoutes = true;

            $defaultOptions = [
                'create' => false,
                'delete' => false,
                'bulk_create' => false,
                'search' => false,
            ];

            foreach ($options as $option) {
                $defaultOptions[$option->value] = true;
            }

            $this->controller(MediaController::class)->group(function () use ($defaultOptions) {
                when($defaultOptions['create'], fn() => $this->post('media', 'create')->name('media.create'));
                when($defaultOptions['delete'], fn() => $this->delete('media/{id}', 'delete')->name('media.delete'));
                when(
                    $defaultOptions['bulk_create'],
                    fn() => $this->post('media/bulk', 'bulkCreate')->name('media.create.bulk')
                );
                when($defaultOptions['search'], fn() => $this->get('media', 'search')->name('media.search'));
            });
        };
    }
}