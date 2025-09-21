<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inschrijving;

class Activiteit extends Model
{
    protected $table = 'activiteiten';
    protected $fillable = ['titel','omschrijving','datum','tijd','locatie','max_deelnemers'];

    public function inschrijvingen()
    {
        return $this->hasMany(Inschrijving::class, 'activiteit_id');
    }
}