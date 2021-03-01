<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logstatus extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function gedung() 
    {
        return $this->belongsTo('App\Gedung', 'gedung_id', 'id');
    }

    // public function details() 
    // {
    //     return $this->hasMany('App\SalesDetails');
    // }

    // public function payments() 
    // {
    //     return $this->hasMany('App\SalesPayments');
    // }

    // public function shipping() 
    // {
    //     return $this->hasOne('App\ShippingsDetails');
    // }

    public function createdUser()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }
}
