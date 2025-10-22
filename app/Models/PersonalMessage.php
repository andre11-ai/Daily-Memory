<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalMessage extends Model{

    protected $fillable = ['from_id', 'to_id', 'message'];

    public function user(){
        return $this->belongsTo(User::class, 'from_id');
    }
}
