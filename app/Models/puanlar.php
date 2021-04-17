<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class puanlar extends Model
{
    protected $fillable = [
        'user_id','paylasimlars_id','verilen_oy'
    ];
}
