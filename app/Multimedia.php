<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    protected $fillable = ['id_model_media','type_model_media','path_media'];
    protected $hidden = ['id','created_at','updated_at'];
}
