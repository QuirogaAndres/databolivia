<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndicatorCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
    ];

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }
}
