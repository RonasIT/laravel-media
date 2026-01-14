<?php

use Illuminate\Support\Facades\Route;
use RonasIT\Media\Http\Controllers\MediaController;
use RonasIT\Media\Http\Middlewares\CheckManuallyRegisteredRoutesMiddleware;

Route::group(['middleware' => CheckManuallyRegisteredRoutesMiddleware::class], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::post('media', [MediaController::class, 'create'])->name('media.base.create');
        Route::delete('media/{id}', [MediaController::class, 'delete'])->name('media.base.delete');
        Route::post('media/bulk', [MediaController::class, 'bulkCreate'])->name('media.base.create.bulk');
    });
    Route::get('media', [MediaController::class, 'search'])->name('media.base.search');
});
