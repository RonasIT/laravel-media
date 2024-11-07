<?php

namespace RonasIT\Media;

use Illuminate\Support\Facades\Config;
use RonasIT\Media\Http\Controllers\MediaController;

class RouteMediaMethods
{
    public function media()
    {
        return function ()
        {
            Config::set('media.standard_routes_loaded',true);

            $this->group([], function (){

                $this->post('media', [MediaController::class, 'create']);

                $this->delete('media/{id}', [MediaController::class, 'delete']);

                $this->post('media/bulk', [MediaController::class, 'bulkCreate']);

                $this->get('media', [MediaController::class, 'search']);
            });
        };
    }
}
