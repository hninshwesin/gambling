<?php

use App\Http\Controllers\Agent\AppUserController as AgentAppUserController;
use App\Http\Controllers\Agent\ClientRegisterController as AgentClientRegisterController;
use App\Http\Controllers\Agent\DepositController as AgentDepositController;
use App\Http\Controllers\Agent\WithdrawController as AgentWithdrawController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AgentRegisterController;
use App\Http\Controllers\AppUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientRegisterController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/login/agent', [LoginController::class, 'showAgentLoginForm']);
Route::get('/login/client', [LoginController::class, 'showClientLoginForm']);
Route::get('/register/agent', [RegisterController::class, 'showAgentRegisterForm']);
Route::get('/register/client', [RegisterController::class, 'showClientRegisterForm']);

Route::post('/login/agent', [LoginController::class, 'agentLogin']);
Route::post('/login/client', [LoginController::class, 'clientLogin']);
Route::post('/register/agent', [RegisterController::class, 'createAgent']);
Route::post('/register/client', [RegisterController::class, 'createClient']);

Route::group(['middleware' => 'auth:agent'], function () {
    // Route::view('/agent', 'agent');
    Route::get('/agent/home', [App\Http\Controllers\Agent\HomeController::class, 'index']);
    Route::resource('/agent/client_register', AgentClientRegisterController::class);
    Route::get('/agent/order', [App\Http\Controllers\Agent\HomeController::class, 'order_details']);
    Route::resource('/agent/app_user', AgentAppUserController::class);
    Route::resource('/agent/deposit', AgentDepositController::class);
    Route::resource('/agent/withdraw', AgentWithdrawController::class);
});

Route::group(['middleware' => 'auth:client'], function () {
    Route::view('/client', 'client');
    Route::resource('deposit', DepositController::class);
    Route::resource('withdraw', WithdrawController::class);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/order', [App\Http\Controllers\HomeController::class, 'order_details'])->name('order');
    Route::resource('agent_register', AgentRegisterController::class);
    Route::resource('deposit', DepositController::class);
    Route::resource('withdraw', WithdrawController::class);
    Route::resource('app_user', AppUserController::class);
});

Route::get('logout', [LoginController::class, 'logout']);
