<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Livewire'], function () {
    Route::group(['namespace' => 'Print'], function () {
        Route::get('/print/invoice/{invoice_id}', \App\Livewire\Print\Invoice::class);
        Route::get('/print/kwitansi/{receipt_id}', \App\Livewire\Print\Receipt::class);
    });
});

Route::get('/print/ship-service/{spk_pandu_id}', \App\Livewire\Print\ShipService::class)->name('cetak-layanan-kapal');
