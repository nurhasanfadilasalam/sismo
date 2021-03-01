<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Perangkat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_perangkat', 'ip_perangkat', 'gedung'
    ];
    
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    function gedung() 
    {
        return $this-belongTo('App\Gedung');
    }

    // function status() 
    // {
    //     return $this-belongTo('App\Status');
    // }
}
