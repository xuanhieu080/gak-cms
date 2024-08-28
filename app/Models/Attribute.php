<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attribute extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group_id'
    ];

    public function group(): HasOne
    {
        return $this->hasOne(AttributeGroup::class, 'id', 'group_id');
    }
}
