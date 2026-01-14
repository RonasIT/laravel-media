<?php

namespace RonasIT\Media;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use RonasIT\Media\Enums\MediaRouteActionEnum;
use RonasIT\Media\Http\Controllers\MediaController;

class MediaRouter
{
    public function media(): Closure
    {
        return function (MediaRouteActionEnum ...$options) {
            Config::set('media.api_enable', false);

            $defaultOptions = [
                'create' => true,
                'delete' => true,
                'bulk_create' => true,
                'search' => true,
            ];

            if (!empty($options)) {
                $options = collect($options);

                $defaultOptions = Arr::map($defaultOptions, fn ($value, $defaultOption) => $options->contains('value', $defaultOption));
            }

            $this->controller(MediaController::class)->group(function () use ($defaultOptions) {
                when($defaultOptions['create'], fn () => $this->post('media', 'create')->name('media.create'));
                when($defaultOptions['delete'], fn () => $this->delete('media/{id}', 'delete')->name('media.delete'));
                when($defaultOptions['bulk_create'], fn () => $this->post('media/bulk', 'bulkCreate')->name('media.create.bulk'));
                when($defaultOptions['search'], fn () => $this->get('media', 'search')->name('media.search'));
            });
        };
    }
}
