<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueCounter extends Model
{
    protected $fillable = [
        'date',
        'current_number',
        'updated_by_admin_id',
    ];
}

