<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    public function users()
    {
        return $this->hasMany('App\User','jurusan_id');
    }
}
