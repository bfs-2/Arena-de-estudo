<?php

use App\Http\Controllers\QuestaoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimuladoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('simulado');
});

Route::get('/simulado', function () {
    return view('simulado');
})->name('simulado');

Route::prefix('api')->name('api.')->group(function () {
    Route::get('/questoes/contagem', [QuestaoController::class, 'contagem'])
        ->name('questoes.contagem');

    Route::get('/questoes', [QuestaoController::class, 'index'])
        ->name('questoes.index');

    Route::middleware('auth')->group(function () {
        Route::get('/simulados', [SimuladoController::class, 'index'])
            ->name('simulados.index');

        Route::post('/simulados', [SimuladoController::class, 'store'])
            ->name('simulados.store');

        Route::delete('/simulados', [SimuladoController::class, 'destroy'])
            ->name('simulados.destroy');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
