<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'city', 'state'];

    protected $with = [
        'forecasts',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function forecasts(): HasMany
    {
        return $this->hasMany(LocationForecast::class);
    }
}
