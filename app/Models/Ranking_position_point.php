<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranking_position_point extends Model
{
    use HasFactory;

    public function ranking()
    {
        return $this->belongsTo(Ranking::class);
    }
}
