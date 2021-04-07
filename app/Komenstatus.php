<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komenstatus extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Status','status_id');
    }
}
