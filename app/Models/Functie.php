<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Functie extends Model
{
    use HasFactory;

    protected $table = 'functies';

    protected $fillable = [
        'naam',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_functions', 'functie_id', 'user_id');
    }
}