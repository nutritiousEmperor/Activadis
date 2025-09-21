<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inschrijving extends Model
{
    protected $table = 'inschrijvingen';

    protected $fillable = [
        'activiteit_id',
        'user_id',
        'guest_email',
    ];

    public function activiteit()
    {
        return $this->belongsTo(Activiteit::class, 'activiteit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
