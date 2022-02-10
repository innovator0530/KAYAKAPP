<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
    ];

    public function ranking_position_points()
    {
        return $this->hasMany(Ranking_position_point::class);
    }
}
