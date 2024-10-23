<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeGroup(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class);
    }
}
