<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Authenticatable
{
    use HasFactory;

    protected $guard = 'agent';

    protected $fillable = [
        'name', 'email', 'password', 'have_client', 'user_id', 'total_balance'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function agent_withdraws()
    {
        return $this->hasMany(AgentWithdraw::class);
    }

    public function agent_deposits()
    {
        return $this->hasMany(AgentDeposit::class);
    }
}
