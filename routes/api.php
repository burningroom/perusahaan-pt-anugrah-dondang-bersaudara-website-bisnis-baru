<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestPanduController;
use App\Http\Controllers\InaportnetController;
use Illuminate\Support\Facades\Route;

Route::match(['GET', 'POST'], 'services/inaportnet', [InaportnetController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);

//    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
//        Route::get('/', 'profile');
//        Route::post('/update-company-data', 'updateCompanyData');
//    });

    Route::prefix('agent')->group(function () {
        Route::controller(RequestPanduController::class)->prefix('arrival-request')->group(function () {
            Route::get('vessel-list', 'vesselList');
            Route::get('jenis-pengolongan-list', 'jenisPengolonganList');
            Route::post('store', 'store');
            Route::get('list', 'indexAgent');
            Route::get('detail/{id}', 'showAgent');
        });

//        Route::controller(StevedoringRequestController::class)->prefix('stevedoring-request')->group(function() {
//            Route::post('store', 'store');
//            Route::get('list', 'list');
//            Route::get('detail/{id}', 'detail');
//        });
//
//        Route::controller(VesselController::class)->prefix('vessel')->group(function() {
//            Route::post('store-update', 'storeUpdate');
//            Route::get('list', 'list');
//            Route::get('detail/{id}', 'detail');
//            Route::delete('delete/{id}', 'delete');
//        });
//
//        Route::controller(InvoiceController::class)->prefix('invoice')->group(function() {
//            Route::get('invoice','invoice');
//            Route::get('detail/{invoice_id}','detail');
//            Route::get('invoice-print/{invoice_id}','invoicePrint');
//            Route::get('item','item');
//            Route::get('total','total');
//            Route::get('receipt','receipt');
//            Route::get('receipt-new','receiptNew');
//            Route::get('receipt/{receipt_id}','receiptDetail');
//            // Route::get('receiavable-invoice','invoiceReceivable');
//        });
//
//        Route::controller(DepositController::class)->prefix('deposit')->group(function() {
//            Route::get('saldo','deposit');
//            Route::post('saldo/create','create');
//            Route::get('saldo/history','history');
//            // Route::get('receiavable-invoice','invoiceReceivable');
//        });
    });
//
    Route::prefix('pandu')->group(function () {
        Route::controller(RequestPanduController::class)->prefix('arrival-request')->group(function () {
            Route::get('list', 'indexPandu');
            Route::get('history', 'history');
            Route::get('detail/{id}', 'showPandu');
            Route::post('submit-spk-pandu', 'submitSPKPandu');
            Route::get('export-ship-service/{id}', 'exportShipService');
        });
    });

});

//Route::controller(InaportnetController::class)->prefix('sudo-inaportnet')->group(function () {
//    Route::get('ppk-for-rpkro', 'ppkForRPKRO');
//    Route::get('get-entry-rkbm', 'getEntryRKBM');
//});

