<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Operator extends Authenticatable
{
    use HasFactory;

    protected $rememberTokenName = false;

    protected $guarded = [
        'id',
        'password',
    ];

    protected $hidden = [
        'id',
        'password',
    ];
}
