<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductWarehouse extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'variant_id',
        'warehouse_id',
        'qty',
        'created_by',
        'updated_by'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function warehouse(): BelongsTo
    {

    }
}
