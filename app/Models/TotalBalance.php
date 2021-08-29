<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalBalance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function app_user()
    {
        return $this->belongsTo(AppUser::class);
    }
}
