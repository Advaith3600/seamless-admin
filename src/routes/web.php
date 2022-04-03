<?php

use Illuminate\Support\Facades\Route;
use Advaith\SeamlessAdmin\Http\Controllers\AdminController;

Route::prefix('admin')
    ->as('admin.')
    ->middleware('web')
    ->group(function () {
        // welcome screen for the admin panel
        Route::get('', [AdminController::class, 'welcome'])->name('welcome');
        // dynamic routing for the CRUD operations
        Route::resource('type', AdminController::class);
    });
