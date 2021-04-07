<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artikelcategory extends Model
{
    public function articles()
    {
        return $this->hasMany('App\Artikel','categoryartikel_id');
    }
}
