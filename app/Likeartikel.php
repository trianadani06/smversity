<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Likeartikel extends Model
{
    public function artikel()
    {
        return $this->belongsTo('App\Artikel','artikel_id');
    }
}
