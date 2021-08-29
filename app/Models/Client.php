<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Authenticatable
{
    use HasFactory;

    protected $guard = 'client';

    protected $fillable = [
        'name', 'email', 'password', 'have_sub_client'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
