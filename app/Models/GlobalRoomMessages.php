<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalRoomMessages extends Model
{
    protected $table="global";
    protected $guarded= [];
    protected $hidden = ['password','updated_at'];
}
