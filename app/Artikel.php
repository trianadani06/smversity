<?php

namespace App;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    public function category()
    {
        return $this->belongsTo('App\Artikelcategory','categoryartikel_id');
    }

    public function likes()
    {
        return $this->hasMany('App\Likeartikel','artikel_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_NIM');
    }

    public function komenartikels()
    {
        return $this->hasMany('App\Komenartikel','artikel_id');
    }

}
