<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ toegevoegd

class Activity extends Model
{
    
    protected $fillable = [
            'title',
            'description',
            'date',
            'time',
            'location',
            'max_participants',
        'min_participants',
            'gasten',
        ];

    protected $casts = [
        'gasten'            => 'boolean',
        'max_participants'  => 'integer',
        'min_participants'  => 'integer',
        'date'              => 'date',
    ];

    protected $appends = ['can_start'];

    protected $dates = ['deleted_at']; // ✅ Laravel weet dat dit een datumveld is

    public function inschrijvingen()
    {
        return $this->hasMany(\App\Models\Inschrijving::class, 'activity_id');
    }
    
    }