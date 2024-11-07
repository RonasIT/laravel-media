<?php

namespace RonasIT\Media;

use Illuminate\Support\Facades\Config;
use RonasIT\Media\Http\Controllers\MediaController;
use RonasIT\Media\Models\Media;

class RouteMediaMethods
{

    public function media()
    {
        return function ()
        {
            $this->getRoutes()->remove('media.base.create');

            $this->group([], function (){

                $this->post('media', [MediaController::class, 'create'])->name('media.create');

                $this->delete('media/{id}', [MediaController::class, 'delete'])->name('media.delete');

                $this->post('media/bulk', [MediaController::class, 'bulkCreate'])->name('media.bulk.create');

                $this->get('media', [MediaController::class, 'search'])->name('media.search');
            });
        };
    }
}
