<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Participant;
use App\Models\Competition;
use App\Models\Category;
use App\Models\Modality;
use App\Models\Round_heat;
use App\Models\Heat_score;

class Com_cat_mod_participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'participant_id',
        'category_id',
        'modality_id',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
    public function modality()
    {
        return $this->belongsTo(Modality::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function round_heats()
    {
        return $this->hasMany(Round_heat::class);
    }
    public function heat_scores()
    {
        return $this->hasMany(Heat_score::class);
    }
}
