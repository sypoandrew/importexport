<?php

use Illuminate\Support\Facades\Route;
use Sypo\ImportExport\Http\Controllers\ModuleController;

Route::get('importexport', [ModuleController::class, 'index'])->name('admin.modules.importexport');
Route::post('importexport', [ModuleController::class, 'update'])->name('admin.modules.importexport');
Route::get('importexport/get_export_all_products', [ModuleController::class, 'get_export_all_products'])->name('admin.modules.importexport.get_export_all_products');
Route::get('importexport/get_export_fine_wine', [ModuleController::class, 'get_export_fine_wine'])->name('admin.modules.importexport.get_export_fine_wine');
Route::get('importexport/get_export_product_stock_price', [ModuleController::class, 'get_export_product_stock_price'])->name('admin.modules.importexport.get_export_product_stock_price');
