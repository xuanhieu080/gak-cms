<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class District extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'full_name',
        'full_name_en',
        'code_name',
        'province_code',
        'province_id',
    ];

    public function ward(): HasMany
    {
        return $this->hasMany(Ward::class, 'district_id', 'id');
    }

    public function province(): HasOne
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }
}
