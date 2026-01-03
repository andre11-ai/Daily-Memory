<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable{

    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'appe',
        'email',
        'username',
        'password',
        'role',
        'profile_image',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function chatGroups(){
        return $this->belongsToMany(ChatGroup::class, 'chat_group_user');
    }

    public function gameScores()
    {
        return $this->hasMany(\App\Models\GameScore::class, 'user_id', 'id');
    }
}
