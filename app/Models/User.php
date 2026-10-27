<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'lastname', 'name', 'patronymic', 'birthday', 'city',
        'organization', 'position', 'phone', 'email',
    ];

    protected $hidden = ['token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
