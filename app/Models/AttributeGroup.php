<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeGroup extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class, 'group_id', 'id');
    }

    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class, 'attribute_group_id', 'id');
    }
}
