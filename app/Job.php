<?php

namespace App;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
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
    public function jurusans()
    {
        return $this->hasMany('App\Jobjurusan','job_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_NIM');
    }
}
