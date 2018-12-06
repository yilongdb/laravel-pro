<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $primaryKey = 'component_id';

    protected $fillable = ['name' , 'file_id'];

    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }


    public function layers(){
        return $this->hasMany('App\Models\Layer');
    }
}
