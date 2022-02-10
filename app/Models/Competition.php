<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Competition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'place',
        'description',
        'date',
        'time',
        'ranking_score',
        'lycras',
        'competition_type_id',
        'status_id',
    ];

    protected $casts = [
        'date' => 'datetime:d-m-Y', // Change your format
        'lycras' => 'array',
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function getDateAttribute()
    {
        $value = $this->attributes['date'];
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function competition_type()
    {
        return $this->belongsTo(Competition_type::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function modalities()
    {
        return $this->belongsToMany(Modality::class, 'modality_competition');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_competition');
    }
}
