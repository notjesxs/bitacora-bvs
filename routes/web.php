<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BitacoraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

Route::get('/bitacoras-exportar', [BitacoraController::class, 'exportar'])
    ->name('bitacoras.exportar');

Route::get('/bitacoras-generar-ppt', [BitacoraController::class, 'generarPpt'])
    ->name('bitacoras.generarPpt');

Route::post('/bitacoras/generar-pdf', [BitacoraController::class, 'generarPdf'])
    ->name('bitacoras.generarPdf');

Route::middleware(['auth'])->group(function () {

    // Bitácoras
    Route::resource('bitacoras', BitacoraController::class);

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';