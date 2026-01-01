<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'date',
        'number',
        'status',
        'called_at',
    ];
}

