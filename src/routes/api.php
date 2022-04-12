<?php

use Illuminate\Support\Facades\Route;
use Advaith\SeamlessAdmin\Http\Controllers\APIController;

Route::prefix(config('seamless-admin.api_prefix'))
    ->as('api.admin.')
    ->middleware(['web', 'api'])
    ->group(function () {
        Route::get('{type}/search_foreign_references', [APIController::class, 'search_foreign_references'])
            ->name('type.search_foreign_references');

        Route::get('{type}/type_index_data', [APIController::class, 'type_index_data'])
            ->name('type.type_index_data');
    });
