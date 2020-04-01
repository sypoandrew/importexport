<?php

use Illuminate\Support\Facades\Route;
use Sypo\ImportExport\Http\Controllers\ModuleController;

Route::get('importexport', [ModuleController::class, 'index'])->name('admin.modules.importexport');
Route::post('importexport', [ModuleController::class, 'update'])->name('admin.modules.importexport');
