<?php

use Illuminate\Support\Facades\Route;
use Raitone\Blook\Controllers\BlookController as BC;

# Iframe used
Route::get('/blook/show/{component}', [BC::class, 'show'])->name('blook.show');
Route::get('/blook/show/{component}/variation/{variation}', [BC::class, 'show'])->name('blook.show.variation');

# App used
Route::get('/blook/{component?}', [BC::class, 'index'])->name('blook.index');
Route::get('/blook/{component?}/variation/{variation}', [BC::class, 'index'])->name('blook.component.variation');
