<?php

use Illuminate\Support\Facades\Route;
use Advaith\SeamlessAdmin\Http\Controllers\AdminController;

Route::prefix('admin')
    ->as('admin.')
    ->middleware('web')
    ->group(function () {
        // welcome screen for the admin panel
        Route::get('', [AdminController::class, 'welcome'])->name('welcome');
        // route for deleting the types
        Route::get('{type}/delete', [AdminController::class, 'delete'])->name('type.delete');
        Route::delete('{type}/delete', [AdminController::class, 'destroy'])->name('type.destroy');
        // dynamic routing for the CRUD operations
        Route::resource('{type}', AdminController::class)
            ->except(['destroy'])
            ->parameters(['{type}' => 'id'])
            ->names('type');
    });
