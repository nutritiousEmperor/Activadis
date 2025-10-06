<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFunction extends Model
{
    use HasFactory;

    protected $table = 'user_functions';

    protected $fillable = [
        'user_id',
        'functie_id',
    ];

    // Relatie naar User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relatie naar Functie
    public function functie()
    {
        return $this->belongsTo(Functie::class);
    }
}
