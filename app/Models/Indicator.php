<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Indicator extends Model
{
    protected $fillable = [
        'indicator_category_id',
        'code',
        'name',
        'description',
        'unit',
        'active',
        'featured',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(IndicatorCategory::class, 'indicator_category_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(IndicatorValue::class);
    }

    public function syncLogs(): HasMany
    {
        return $this->hasMany(SyncLog::class);
    }
}
