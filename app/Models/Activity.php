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

    public function inschrijvingen()
    {
        return $this->hasMany(\App\Models\Inschrijving::class, 'activity_id');
    }

    public function getCanStartAttribute(): bool
    {
        $min = (int) ($this->min_participants ?? 0);

        $count = isset($this->inschrijvingen_count)
            ? (int) $this->inschrijvingen_count
            : (int) $this->inschrijvingen()->count();

        return $count >= $min;
    }
}
