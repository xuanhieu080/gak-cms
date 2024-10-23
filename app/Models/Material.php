<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'name'
    ];

    public function materialWarehouses(): HasMany
    {
        return $this->hasMany(MaterialWarehouse::class, 'material_id', 'id');
    }
}
