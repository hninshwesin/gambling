<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Client extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $guard = 'client';

    protected $fillable = [
        'phone_number', 'email', 'password', 'have_sub_client', 'agent_id', 'parent_client_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function total_balances()
    {
        return $this->hasOne(TotalBalance::class);
    }

    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }
}
