<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layer extends Model
{
    protected $primaryKey = 'layer_id';

    protected $fillable = ['name' , 'component_id' , 'parent_id' , 'status' , 'type'];

    public function component()
    {
        return $this->belongsTo('App\Models\Component');
    }
}
