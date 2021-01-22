<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;

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
    
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function tracks() 
    {
        return $this->hasMany('App\InventoriesTracks');
    }

    public function purchases() 
    {
        return $this->hasMany('App\Purchases', 'id', 'created_by');
    }

    public function shippings() 
    {
        return $this->hasMany('App\Shippings', 'id', 'created_by');
    }

    public function shippingsDetails() 
    {
        return $this->hasMany('App\ShippingsDetails', 'id', 'proccess_by');
    }

    public function spend() 
    {
        return $this->hasMany('App\Spend', 'id', 'created_by');
    }
}
