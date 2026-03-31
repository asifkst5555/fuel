<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrowdReport extends Model
{
    use HasFactory;

    public const LEVEL_LOW = 'low';
    public const LEVEL_MEDIUM = 'medium';
    public const LEVEL_HIGH = 'high';
    public const LEVEL_SEVERE = 'severe';

    protected $fillable = [
        'station_id',
        'crowd_level',
        'ip_address',
    ];

    public static function labels(): array
    {
        return [
            self::LEVEL_LOW => 'ভিড় নেই',
            self::LEVEL_MEDIUM => 'মাঝারি ভিড়',
            self::LEVEL_HIGH => 'প্রচুর ভিড়',
            self::LEVEL_SEVERE => 'ভয়াবহ অবস্থা',
        ];
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
}
