<?php

namespace RonasIT\Media;

use RonasIT\Media\Http\Controllers\MediaController;

class RouteMediaMethods
{
    public function media()
    {
        $options = [
            'controller' => MediaController::class,

            'media_post' => $options['media_post'] ?? true,
            'media_delete' => $options['media_delete'] ?? true,
            'media_bulk' => $options['media_bulk'] ?? true,
            'media_search' => $options['media_search'] ?? true,
        ];

        $this->group([], function () use ($options) {
            $this->post('media', [MediaController::class, 'create']);

            $this->delete('media/{id}', [MediaController::class, 'delete']);

            $this->post('media/bulk', [MediaController::class, 'bulkCreate']);

            $this->get('media', [MediaController::class, 'search']);
        });
    }
}