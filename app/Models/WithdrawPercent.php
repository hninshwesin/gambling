<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawPercent extends Model
{
    use HasFactory;

    protected $fillable = ['admin_percent', 'agent_percent'];
}
