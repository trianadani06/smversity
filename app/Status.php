<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function komenstatuses()
    {
        return $this->hasMany('App\Komenstatus','status_id');
    }

    public function likes()
    {
        return $this->hasMany('App\Like','status_id');
    }

    public function ukm()
    {
        return $this->belongsTo('App\Ukm','ukm_id');
    }
}
