<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variant extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'is_active',
        'price',
        'price_sale',
        'sku',
        'created_by',
        'updated_by'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


}
