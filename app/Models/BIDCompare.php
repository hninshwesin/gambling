<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BIDCompare extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Order()
    {
        return $this->belongsToMany(Order::class);
    }
}
