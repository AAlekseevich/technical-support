<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_manager',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isManager()
    {
        return !empty($this->is_manager);
    }

    public function isMember()
    {
        return !$this->isManager();
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
