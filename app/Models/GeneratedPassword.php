<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedPassword extends Model
{
    protected $table = 'passwords';

    protected $fillable = [
        'password',
        'length',
        'uppercase',
        'lowercase',
        'numbers',
        'symbols',
    ];

    protected $casts = [
        'length' => 'integer',
        'uppercase' => 'boolean',
        'lowercase' => 'boolean',
        'numbers'   => 'boolean',
        'symbols'   => 'boolean',
    ];
}
