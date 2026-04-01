<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Station extends Model
{
    /** @use HasFactory<\Database\Factories\StationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'dealer',
    ];

    public function fuelStatus(): HasOne
    {
        return $this->hasOne(FuelStatus::class);
    }

    public function crowdReports(): HasMany
    {
        return $this->hasMany(CrowdReport::class);
    }

    public function latestCrowdReport(): HasOne
    {
        return $this->hasOne(CrowdReport::class)->latestOfMany();
    }

    public function fuelStatuses(): HasMany
    {
        return $this->hasMany(FuelStatus::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
