<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Com_cat_mod_participant;
use App\Models\Lycra;

class Round_heat extends Model
{
    use HasFactory;

    protected $fillable = [
        'com_cat_mod_participant_id',
        'lycra_id',
        'round',
        'heat',
        'first_score',
        'second_score',
        'points',
        'position',
        'status',
        'penal',
        'draw',
    ];

    public function com_cat_mod_participant()
    {
        return $this->belongsTo(Com_cat_mod_participant::class);
    }
    public function lycra()
    {
        return $this->belongsTo(Lycra::class);
    }
}
