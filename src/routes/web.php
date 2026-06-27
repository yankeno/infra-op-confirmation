<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProbeFileController;
use App\Http\Controllers\SessionProbeController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::get('/health', [HealthController::class, 'health'])->name('health');
Route::get('/ready', [HealthController::class, 'ready'])->name('ready');
Route::get('/whoami', [HealthController::class, 'whoami'])->name('whoami');

Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');
Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

Route::get('/files', [ProbeFileController::class, 'index'])->name('files.index');
Route::post('/files', [ProbeFileController::class, 'store'])->name('files.store');
Route::get('/files/download', [ProbeFileController::class, 'download'])->name('files.download');
Route::delete('/files', [ProbeFileController::class, 'destroy'])->name('files.destroy');

Route::get('/session', [SessionProbeController::class, 'show'])->name('session.show');
Route::post('/session', [SessionProbeController::class, 'update'])->name('session.update');
