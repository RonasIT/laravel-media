<?php

namespace RonasIT\Media;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use RonasIT\Media\Http\Controllers\MediaController;
use RonasIT\Media\Models\Media;

class RouteMediaMethods
{

    public function media()
    {
        return function (array $options = []) {

            MediaServiceProvider::$isBlockedBaseRoutes = true;

            $options = [
                'create' => $options['create'] ?? true,
                'delete' => $options['delete'] ?? true,
                'bulk_create' => $options['bulk_create'] ?? true,
                'search' => $options['search'] ?? true,
            ];

            $this->controller(MediaController::class)->group(function () use ($options) {

                $this->group([], function () use ($options) {

                    if (!$options['create']) {
                        return;
                    }

                    $this->post('media', 'create')->name('media.create');
                });

                $this->group([], function () use ($options) {

                    if (!$options['delete']) {
                        return;
                    }

                    $this->delete('media/{id}', 'delete')->name('media.delete');
                });

                $this->group([], function () use ($options) {

                    if (!$options['bulk_create']) {
                        return;
                    }

                    $this->post('media/bulk', 'bulkCreate')->name('media.bulk.create');
                });

                $this->group([], function () use ($options) {

                    if (!$options['search']) {
                        return;
                    }

                    $this->get('media', 'search')->name('media.search');
                });
            });
        };
    }
}