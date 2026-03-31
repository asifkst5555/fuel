<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class FuelStatus extends Model
{
    /** @use HasFactory<\Database\Factories\FuelStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'station_id',
        'octane',
        'diesel',
    ];

    protected function casts(): array
    {
        return [
            'octane' => 'boolean',
            'diesel' => 'boolean',
            'updated_at' => 'datetime',
        ];
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
}
