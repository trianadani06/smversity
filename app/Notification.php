<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function sender()
    {
        return $this->belongsTo('App\User','sender_id');
    }

    public function follow()
    {
        return $this->belongsTo('App\Follow','not_id');
    }
}
