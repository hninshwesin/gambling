<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepositResourceCollection;
use App\Http\Resources\TotalBalaceResource;
use App\Http\Resources\TotalBalaceResourceCollection;
use App\Http\Resources\WithdrawResourceCollection;
use App\Models\AppUser;
use App\Models\Client;
use App\Models\Deposit;
use App\Models\TotalBalance;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TotalBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        $total_balance = TotalBalance::where('client_id', $app_user->id)->get();

        return new TotalBalaceResourceCollection($total_balance);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deposit_history()
    {
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        if ($app_user) {
            $deposits = Deposit::where('client_id', $app_user->id)->get();

            return new DepositResourceCollection($deposits);
        }
    }

    public function withdraw_history()
    {
        $user = Auth::guard('client-api')->user();
        $app_user = Client::find($user->id);

        if ($app_user) {
            $withdraws = Withdraw::where('client_id', $app_user->id)->get();

            return new WithdrawResourceCollection($withdraws);
        }
    }
}
