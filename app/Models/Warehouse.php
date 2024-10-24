<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Warehouse extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'name',
        'address',
        'phone',
        'email',
        'manager_id',
        'tax_code',
    ];

    public function manager(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'id', 'manager_id');
    }
}
