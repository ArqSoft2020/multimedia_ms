<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    protected $fillable = ['multiable_id','multiable_type','multiable_path'];
    protected $hidden = ['id','created_at','updated_at'];
}
