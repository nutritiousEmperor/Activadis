<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    
    protected $fillable = [
    'title',
    'description',
    'date',
    'time',
    'location',
    'max_participants',
];

}
