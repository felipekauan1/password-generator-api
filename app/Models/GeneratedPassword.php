<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedPassword extends Model
{
    protected $fillable = [
        'password',
        'length',
        'uppercase',
        'lowercase',
        'numbers',
        'symbols',
    ];
}
