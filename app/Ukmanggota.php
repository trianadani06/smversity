<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ukmanggota extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function ukm()
    {
        return $this->belongsTo('App\Ukm','ukm_id');
    }
}
