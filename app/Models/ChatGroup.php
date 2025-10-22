<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model{
    
    protected $fillable = ['name', 'admin_id'];

    public function users(){
        return $this->belongsToMany(User::class, 'chat_group_user');
    }

    public function messages(){
        return $this->hasMany(ChatGroupMessage::class);
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id');
    }
}
