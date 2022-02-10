<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'dni_ficha',
        'birthday',
        'sex_id',
        'club_id',
        'ranking',
    ];

    protected $casts = [
        'birthday' => 'datetime:d-m-Y', // Change your format
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['birthday'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function getDateAttribute()
    {
        $value = $this->attributes['birthday'];
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function sex()
    {
        return $this->belongsTo(Sex::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
