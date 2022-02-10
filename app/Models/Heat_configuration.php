<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heat_configuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_number',
        'assign_array',
    ];

    protected $casts = [
        'assign_array' => 'array',
    ];
}
