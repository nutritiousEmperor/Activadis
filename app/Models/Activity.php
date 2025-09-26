<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'max_participants',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];
}
