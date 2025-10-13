<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ toegevoegd

class Activity extends Model
{
    use SoftDeletes; // ✅ zorgt voor soft delete-functionaliteit

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

    protected $dates = ['deleted_at']; // ✅ Laravel weet dat dit een datumveld is

    public function inschrijvingen()
    {
        return $this->hasMany(\App\Models\Inschrijving::class, 'activity_id');
    }
}
