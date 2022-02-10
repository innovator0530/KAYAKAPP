<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Round_heat;
use App\Models\User;

class Heat_score extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_heat_id',
        'judge_id',
        'wave_1',
        'wave_2',
        'wave_3',
        'wave_4',
        'wave_5',
        'wave_6',
        'wave_7',
        'wave_8',
        'wave_9',
        'wave_10',
        'penal',
    ];

    public function round_heat()
    {
        return $this->belongsTo(Round_heat::class);
    }
    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }
}
