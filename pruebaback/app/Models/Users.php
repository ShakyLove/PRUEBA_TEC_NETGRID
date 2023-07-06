<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = "users";

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'password',
        'rol_id',
    ];
}
