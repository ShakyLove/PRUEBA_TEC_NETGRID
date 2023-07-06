<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $table = "tasks";

    protected $fillable = [
        'title',
        'detail',
        'state_id',
        'project_id',
    ];
}
