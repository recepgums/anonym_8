<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paylasimlar extends Model
{
    protected $fillable = [
        'user_id','baslik','paylasim','puan'
    ];
}
