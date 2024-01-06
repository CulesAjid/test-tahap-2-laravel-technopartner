<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
    Route::post('insert', [TransactionController::class, 'insert'])->name('insert');
    Route::get('response-sample', [TransactionController::class, 'responseSample'])->name('response-sample');
    Route::post('validation', [TransactionController::class, 'addTransactionValidation'])->name('add-transaction-validation');
    Route::put('validation/{id}', [TransactionController::class, 'updateTransactionValidation'])->name('update-transaction-validation');
});
Route::apiResource('transactions', TransactionController::class);
