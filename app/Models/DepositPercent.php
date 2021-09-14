<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositPercent extends Model
{
    use HasFactory;

    protected $fillable = ['admin_percent', 'agent_percent', 'client_percent'];
}
