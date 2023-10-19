<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

use Raitone\Blook\Controllers\BlookController;

$inValidEnvironment = App::environment(config('blook.enabled_environments'));

if($inValidEnvironment){
    Route::prefix(config("blook.base_route"))->group(function () {
        Route::name('blook.')->group(function () {
            Route::controller(BlookController::class)->group(function () {
    
                # Iframe
                Route::get('/show/{component}', 'show')->name('show');
                Route::get('/show/{component}/variation/{variation}', 'show')->name('show.variation');
                
                # Interface
                Route::get('/{component?}', 'index')->name('index');
                Route::get('/{component?}/variation/{variation}','index')->name('component.variation');
    
            });
        });
    });
}
