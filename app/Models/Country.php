<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'code',
        'iso2_code',
        'name',
        'region',
        'income_level',
        'active',
    ];

    public function indicatorValues(): HasMany
    {
        return $this->hasMany(IndicatorValue::class);
    }
}
