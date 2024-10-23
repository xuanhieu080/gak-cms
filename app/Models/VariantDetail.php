<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VariantDetail extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'variant_id',
        'attribute_id',
        'attribute_group_id',
        'created_by',
        'updated_by'
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function attribute(): HasOne
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function attributeGroup(): HasOne
    {
        return $this->hasOne(AttributeGroup::class, 'id', 'attribute_group_id');
    }
}
