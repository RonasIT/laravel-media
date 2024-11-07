<?php

namespace RonasIT\Media;

use RonasIT\Media\Http\Controllers\MediaController;

class RouteMediaMethods
{
    public function media()
    {
        return function () {

            $this->group([], function (){

                $this->post('media', [MediaController::class, 'create']);

                $this->delete('media/{id}', [MediaController::class, 'delete']);

                $this->post('media/bulk', [MediaController::class, 'bulkCreate']);

                $this->get('media', [MediaController::class, 'search']);
            });
        };
    }
}
