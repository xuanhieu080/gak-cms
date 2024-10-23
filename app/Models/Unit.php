<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends BaseModel
{
    use HasFactory;

    protected $fillable = [
      'id',
      'name',
      'code',
      'is_active',
      'created_by',
      'updated_by',
    ];

}
