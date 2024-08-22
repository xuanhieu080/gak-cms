<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $table = 'permission_groups';

    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function permissions() {
        return $this->hasMany(Permission::class, 'group_id', 'id');
    }
}
