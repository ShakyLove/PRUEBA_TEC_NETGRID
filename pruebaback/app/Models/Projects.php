<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $table = "projects";

    protected $fillable = [
        'title',
        'detail',
        'date_init',
        'date_finish',
        'user_id',
    ];
}
