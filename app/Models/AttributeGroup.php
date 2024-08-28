<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'group_id', 'id');
    }
}
