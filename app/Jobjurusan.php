<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobjurusan extends Model
{
    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan','jurusan_id');
    }
}
