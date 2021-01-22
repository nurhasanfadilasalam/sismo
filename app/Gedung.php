<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gedung extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_gedung',
        'kode_gedung',
    ];
    
    use SoftDeletes;

    protected $table = 'gedung';
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['deleted_at'];

    public function perangkat() 
    {
        return $this->hasMany('App\Perangkat');
    }

    // public function shippingsDetails() 
    // {
    //     return $this->hasMany('App\ShippingsDetails');
    // }
    
    // public function routesDetails() 
    // {
    //     return $this->hasOne('App\RoutesDetails')->orderBy('order');
    // }
}
