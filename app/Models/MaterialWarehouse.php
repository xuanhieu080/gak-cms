<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialWarehouse extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'material_id',
        'warehouse_id',
        'qty',
        'created_by',
        'updated_by'
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
