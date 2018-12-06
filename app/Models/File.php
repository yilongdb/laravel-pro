<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $primaryKey = 'file_id';

    protected $hidden = ['user_id'];

    protected $fillable = ['name' , 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function components(){
        return $this->hasMany('App\Models\Component');
    }

    public function tokens(){
        return $this->hasMany('App\Models\Token');
    }
}
