<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BitacoraController;

Route::get('/', function () {
    return redirect()->route('bitacoras.index');
})->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/bitacoras-exportar', [BitacoraController::class, 'exportar'])
        ->name('bitacoras.exportar');

    Route::get('/bitacoras-generar-ppt', [BitacoraController::class, 'generarPpt'])
        ->name('bitacoras.generarPpt');

    Route::post('/bitacoras/generar-pdf', [BitacoraController::class, 'generarPdf'])
        ->name('bitacoras.generarPdf');

    Route::resource('bitacoras', BitacoraController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';