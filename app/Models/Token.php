<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $primaryKey = 'token_id';

    protected $fillable = ['name' , 'file_id' , 'value' , 'status' , 'type'];

    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }
}
