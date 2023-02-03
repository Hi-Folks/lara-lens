<?php

use Illuminate\Support\Facades\Route;
use HiFolks\LaraLens\Http\Controllers\LaraLensController;

Route::get('/', [LaraLensController::class, 'index'])->name('laralens.index');
