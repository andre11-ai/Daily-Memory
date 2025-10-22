<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatGroupMessage extends Model{

    protected $fillable = ['chat_group_id', 'user_id', 'message'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function group(){
        return $this->belongsTo(ChatGroup::class, 'chat_group_id');
    }
}
