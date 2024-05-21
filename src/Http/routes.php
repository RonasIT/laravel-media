<?php

use RonasIT\Media\Http\Controllers\MediaController;

Route::group(['middleware' => 'auth'], function () {
    Route::post('media', [MediaController::class, 'create']);
    Route::delete('media/{id}', [MediaController::class, 'delete']);
    Route::post('media/bulk', [MediaController::class, 'bulkCreate']);
});

Route::get('media', [MediaController::class, 'search']);
