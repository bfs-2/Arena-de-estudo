<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestaoController;
use App\Http\Controllers\SimuladoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('public.home');

Route::get('/simulado', function () {
    return view('simulado');
})->middleware('auth')->name('simulado');

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

Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
