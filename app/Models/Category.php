<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'year1',
        'year2',
        'sex_id',
    ];

    public function sex()
    {
        return $this->belongsTo(Sex::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'category_competition');
    }
}
