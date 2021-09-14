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
}
