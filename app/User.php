<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function statuses()
    {
        return $this->hasMany('App\Status','user_id');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan','jurusan_id');
    }

    public function followings()
    {
        return $this->hasMany('App\Follow','user_NIM');
    }

    public function followers()
    {
        return $this->hasMany('App\Follow','following');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification','user_id');
    }

    public function articles()
    {
        return $this->hasMany('App\Artikel','user_NIM');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
