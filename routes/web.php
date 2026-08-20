<?php

use Illuminate\Support\Facades\Route;
use Bocapro\QuickbooksConnector\Http\Controllers\QuickbooksOAuthController;

Route::name('quickbooks-connector.')
    ->prefix(config('quickbooks-connector.routes.prefix'))
    ->middleware(config('quickbooks-connector.routes.middleware'))
    ->group(function () {
        Route::get('connect', [QuickbooksOAuthController::class, 'connect'])
            ->name('connect');

        Route::get('callback', [QuickbooksOAuthController::class, 'callback'])
            ->name('callback');
    });
