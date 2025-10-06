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
    'gasten',
];

    protected $casts = [
        'gasten' => 'boolean',
    ];

       public function inschrijvingen()
    {
        // Maak (desnoods tijdelijk) een simpel model voor de tabel 'inschrijvingen'
        return $this->hasMany(\App\Models\Inschrijving::class, 'activity_id');
    }
    
    }