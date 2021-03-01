<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class Traffic extends Model
{
    protected $fillable = [
       'perangkat_id', 'nilai', 'keterangan'
    ];
    

    function perangkat() 
    {
        return $this-belongsTo('App\Perangkat', 'perangkat_id','id');
    }
}
