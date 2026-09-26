<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'event_date',
        'event_time',
        'media_type',
        'media_path',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
    ];

    public function scopeCronologico(Builder $query, string $direccion = 'asc'): Builder
    {
        return $query->orderBy('event_date', $direccion)->orderBy('event_time', $direccion);
    }
}
