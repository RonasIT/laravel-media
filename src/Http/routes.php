<?php

use RonasIT\Media\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
use RonasIT\Media\Http\Middlewares\BlockMiddleware;

Route::group(['middleware' => BlockMiddleware::class], function (){
    Route::group(['middleware' => 'auth'], function () {
        Route::post('media', [MediaController::class, 'create'])->name('media.base.create');
        Route::delete('media/{id}', [MediaController::class, 'delete'])->name('media.base.delete');
        Route::post('media/bulk', [MediaController::class, 'bulkCreate'])->name('media.base.bulk.create');
    });
    Route::get('media', [MediaController::class, 'search'])->name('media.base.search');
});