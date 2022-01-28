<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function () {
    Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

    Route::group([
        'middleware' => 'auth.client-api'
    ], function () {
        Route::post('generate_code', [\App\Http\Controllers\API\SubClientRegistrationCodeController::class, 'store']);
        Route::post('buy_order_create', [App\Http\Controllers\API\OrderController::class, 'buy']);
        Route::get('order_history', [App\Http\Controllers\API\OrderController::class, 'order_history']);
        Route::get('get_total_balance', [App\Http\Controllers\API\TotalBalanceController::class, 'index']);
        Route::get('service_start', [\App\Http\Controllers\API\ServiceStartController::class, 'index']);
        // Route::get('test', [\App\Http\Controllers\API\ServiceStartController::class, 'test']);
        Route::get('today_order_history', [App\Http\Controllers\API\OrderController::class, 'today_order_history']);
        Route::post('sell_order_create', [App\Http\Controllers\API\OrderController::class, 'sell']);
        Route::get('deposit_history', [App\Http\Controllers\API\DepositController::class, 'deposit_history']);
        Route::get('withdraw_history', [App\Http\Controllers\API\WithdrawController::class, 'withdraw_history']);
        Route::post('deposit_request', [App\Http\Controllers\API\DepositController::class, 'deposit_request']);
        Route::post('withdraw_request', [App\Http\Controllers\API\WithdrawController::class, 'withdraw_request']);
    });
});
