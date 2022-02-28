<?php

use App\Events\CurrentPrice;
use App\Http\Controllers\Agent\AgentWithdrawController as AgentAgentWithdrawController;
use App\Http\Controllers\Agent\AppUserController as AgentAppUserController;
use App\Http\Controllers\Agent\ClientRegisterController as AgentClientRegisterController;
use App\Http\Controllers\Agent\DepositController as AgentDepositController;
use App\Http\Controllers\Agent\WithdrawController as AgentWithdrawController;
use App\Http\Controllers\Agent\ResetPasswordController as AgentResetPasswordController;
use App\Http\Controllers\AgentDetailController;
use App\Http\Controllers\Client\AppUserController as ClientAppUserController;
use App\Http\Controllers\Client\SubClientController as ClientSubClientController;
use App\Http\Controllers\Client\DepositController as ClientDepositController;
use App\Http\Controllers\Client\WithdrawController as ClientWithdrawController;
use App\Http\Controllers\AgentRegisterController;
use App\Http\Controllers\AppUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\DepositPercentController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\WithdrawPercentController;
use App\Models\BIDCompare;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => false
]);

Route::get('/login/agent', [LoginController::class, 'showAgentLoginForm']);
// Route::get('/login/client', [LoginController::class, 'showClientLoginForm']);
Route::get('/register/agent', [RegisterController::class, 'showAgentRegisterForm']);
// Route::get('/register/client', [RegisterController::class, 'showClientRegisterForm']);

Route::post('/login/agent', [LoginController::class, 'agentLogin']);
// Route::post('/login/client', [LoginController::class, 'clientLogin']);
Route::post('/register/agent', [RegisterController::class, 'createAgent']);
// Route::post('/register/client', [RegisterController::class, 'createClient']);

Route::group(['middleware' => 'auth:agent'], function () {
    // Route::view('/agent', 'agent');
    Route::get('/agent/home', [App\Http\Controllers\Agent\HomeController::class, 'index']);
    Route::resource('/agent/client_register', AgentClientRegisterController::class);
    Route::get('/agent/order', [App\Http\Controllers\Agent\HomeController::class, 'order_details']);
    Route::resource('/agent/app_user', AgentAppUserController::class);
    Route::resource('/agent/deposit', AgentDepositController::class);
    Route::resource('/agent/withdraw', AgentWithdrawController::class);
    Route::resource('password', AgentResetPasswordController::class);
    Route::get('/agent/direct_withdraw', [\App\Http\Controllers\Agent\WithdrawController::class, 'direct_withdraw']);
    Route::post('/agent/make_direct_withdraw', [\App\Http\Controllers\Agent\WithdrawController::class, 'make_direct_withdraw']);
    Route::resource('agent/agent_withdraw', AgentAgentWithdrawController::class);
});

// Route::group(['middleware' => 'auth:client'], function () {
//     Route::get('/client/home', [App\Http\Controllers\Client\HomeController::class, 'index']);
//     Route::resource('/client/sub_client_register', ClientSubClientController::class);
//     Route::resource('/client/app_user', ClientAppUserController::class);
//     Route::resource('/client/deposit', ClientDepositController::class);
//     Route::resource('/client/withdraw', ClientWithdrawController::class);
// });

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/order', [App\Http\Controllers\HomeController::class, 'order_details'])->name('order');
    Route::resource('agent_register', AgentRegisterController::class);
    Route::resource('deposit', DepositController::class);
    Route::resource('withdraw', WithdrawController::class);
    Route::resource('app_user', AppUserController::class);
    Route::resource('depositPercents', DepositPercentController::class);
    Route::resource('withdrawPercents', WithdrawPercentController::class);
    Route::get('deposit_request', [App\Http\Controllers\DepositController::class, 'deposit_request'])->name('deposit_request');
    Route::post('deposit_approve', [App\Http\Controllers\DepositController::class, 'deposit_approve'])->name('deposit_approve');
    Route::get('withdraw_request', [App\Http\Controllers\WithdrawController::class, 'withdraw_request'])->name('withdraw_request');
    Route::post('withdraw_approve', [App\Http\Controllers\WithdrawController::class, 'withdraw_approve'])->name('withdraw_approve');
    Route::resource('agent_detail', AgentDetailController::class);
    Route::get('agent_withdraw_request', [\App\Http\Controllers\AgentDetailController::class, 'agent_withdraw_request'])->name('agent_withdraw_request');
    Route::post('agent_withdraw_approve', [\App\Http\Controllers\AgentDetailController::class, 'agent_withdraw_approve'])->name('agent_withdraw_approve');
    Route::get('agent_withdraw_history', [\App\Http\Controllers\AgentDetailController::class, 'agent_withdraw_history'])->name('agent_withdraw_history');
});

Route::get('logout', [LoginController::class, 'logout']);

Route::get('/scheduler', function () {
    // broadcast(new CurrentPrice('200'));

    // $timestamp = Carbon::now()->subMinute(1);
    // dd($timestamp->toDateTimeString());

    // Artisan::call('schedule:run');
    // return true;

    // $start_rate = 1798.02;
    // $response = Http::withHeaders(['x-access-token' => 'goldapi-aaqdoetkunu1104-io'])
    //     ->accept('application/json')
    //     ->get("https://www.goldapi.io/api/XAU/USD");

    // $end_rate = json_decode($response);

    // if ($start_rate < $end_rate->price) {
    //     var_dump('win');
    //     $status = 1;
    // } elseif ($start_rate > $end_rate->price) {
    //     var_dump('loss');
    //     $status = 2;
    // } elseif ($start_rate == $end_rate->price) {
    //     $status = 0;
    // }

    // dd($status);
    // $BID = BIDCompare::create([
    //     'order_id' => 2,
    //     'amount' => 200,
    //     'start_rate' => $start_rate,
    //     'end_rate' => $end_rate,
    //     'status' => $status,
    // ]);
});
