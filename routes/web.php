<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

// Halaman utama transaksi
Route::get('/transaksi', [
    TransaksiController::class,
    'index'
])->name('transaksi.index');

// Export Excel
Route::get('/transaksi/export', [
    TransaksiController::class,
    'export'
])->name('transaksi.export');

// Import Excel
Route::post('/transaksi/import', [
    TransaksiController::class,
    'import'
])->name('transaksi.import');