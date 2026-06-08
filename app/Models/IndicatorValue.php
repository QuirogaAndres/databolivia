<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicatorValue extends Model
{
    protected $fillable = [
        'country_id',
        'indicator_id',
        'year',
        'value',
    ];

    protected $casts = [
        'year' => 'integer',
        'value' => 'decimal:6',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }
}
