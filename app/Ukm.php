<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Ukm extends Model
{   
    use Sluggable;
    
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    public function galleries(){
        return $this->hasMany('App\Ukmgallery','ukm_id');
    }

    public function anggotas(){
        return $this->hasMany('App\Ukmanggota','ukm_id');
    }

    public function statuses()
    {
        return $this->hasMany('App\Status','ukm_id');
    }

}
