<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Com_cat_mod_participant;

class Manage_ranking_point extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'category_id',
        'modality_id',
        'participant_id',
        'ranking',
        'ranking_points',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function modality()
    {
        return $this->belongsTo(Modality::class);
    }
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
